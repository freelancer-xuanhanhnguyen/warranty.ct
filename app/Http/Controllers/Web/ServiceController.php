<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $q = \request()->q;
        $status = \request()->status;
        $query = Service::with([
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])
            ->whereHas('order.customer', function ($query) {
                $query->select('id')->where('id', customer()->id());
            })
            ->when($q, function ($query) use ($q) {
                $q = escape_like($q);
                $query->where('code', 'like', "%{$q}%");
            });

        if (isset($status)) {
            $query = $query->whereHas('status', function ($query) use ($status) {
                $query->select('id')->where('code', $status);
            });
        }

        $sort = \request()->sort ?? [];
        if (\request()->sort) {
            foreach ($sort as $key => $value) {
                $query = $query
                    ->join('orders', 'orders.id', '=', 'services.order_id')
                    ->join('products', 'products.id', '=', 'orders.product_id')
                    ->orderBy(str_replace('__', '.', $key), $value);
            }
        } else {
            $query = $query->latest();
        }

        $data = $query->selectRaw('services.*')
            ->paginate(20);

        return view('pages.services.index', compact('data'));
    }

    public function detail($id)
    {
        $data = Service::with([
            'items.accessory:id,name',
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])
            ->whereHas('order.customer', function ($query) {
                $query->select('id')->where('id', customer()->id());
            })
            ->findOrFail($id);


        return view('pages.services.detail', compact('data'));
    }

    public function request($orderId)
    {
        $order = Order::with('product')
            ->whereHas('customer', function ($query) {
                $query->select('id')->where('id', customer()->id());
            })
            ->findOrFail($orderId, ['id', 'product_id', 'code', 'purchase_date']);

        return view('pages.services.create', compact('order'));
    }

    public function create($orderId, Request $request)
    {
        $order = Order::with('product:id,warranty_period,warranty_period_unit')
            ->findOrFail($orderId, ['id', 'product_id', 'purchase_date']);
        $isWarrantyExpired = isWarrantyExpired($order->purchase_date, $order->product?->warranty_period, $order->product?->warranty_period_unit);
        $type = $isWarrantyExpired ? Service::TYPE_REPAIR : Service::TYPE_WARRANTY;

        DB::beginTransaction();
        $service = Service::with('status')
            ->where([
                ['order_id', $orderId],
                ['type', $type]
            ])
            ->whereHas('status', function ($q) {
                $q->whereNotIn('code', [ServiceStatus::STATUS_COMPLETED, ServiceStatus::STATUS_CANCELED]);
            })->first('id');

        if ($service) return redirect(route('services.detail', [$service->id]))
            ->with(['error' => 'Vui lòng chờ, sản phẩm đang trong quá trình bảo hành - sữa chữa.']);

        $service = Service::create(collect($request->all())->merge([
            'order_id' => $orderId,
            'type' => $type
        ])->toArray());

        if ($service) {
            DB::commit();
            return redirect(route('services.detail', [$service->id]))
                ->with(['message' => "Thêm phiếu " . strtolower(Service::TYPE[$type]) . " thành công."]);
        }

        DB::rollBack();

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    public function review($id, Request $request)
    {
        $service = Service::findOrFail($id);
        if ($service->evaluate > 0) return back()->with(['error' => 'Dịch vụ đã được đánh giá, vui lòng thử lại sau.']);
        if ($request->score > 0) {
            $updated = $service->update(collect($request->only(['score', 'evaluate_note']))->merge([
                'evaluate' => $request->score,
            ])->toArray());

            if ($updated) {
                return back()->with(['message' => "Đánh giá thành công."]);
            }
        }

        return back()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }
}
