<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceStatus;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index($email)
    {
        $q = \request()->q;
        $status = \request()->status;
        $query = Service::with([
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])
            ->whereHas('order.customer', function ($query) use ($email) {
                $query->select('id')->where('email', $email);
            })
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%$q%");
            });

        if (isset($status)) {
            $query = $query->whereHas('status', function ($query) use ($status) {
                $query->select('id')->where('code', $status);
            });
        }

        $data = $query
            ->latest()
            ->paginate(20);

        return view('pages.services.index', compact('data'));
    }

    public function detail($email, $id)
    {
        $data = Service::with([
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])
            ->whereHas('order.customer', function ($query) use ($email) {
                $query->select('id')->where('email', $email);
            })
            ->findOrFail($id);


        return view('pages.services.detail', compact('data'));
    }

    public function request($email, $orderId)
    {
        $order = Order::with('product')
            ->whereHas('customer', function ($query) use ($email) {
                $query->select('id')->where('email', $email);
            })
            ->findOrFail($orderId, ['id', 'product_id', 'code', 'purchase_date']);

        return view('pages.services.create', compact('order'));
    }

    public function create($email, $orderId, Request $request)
    {
        $order = Order::with('product')->findOrFail($orderId);
        $isWarrantyExpired = isWarrantyExpired($order->purchase_date, $order->product?->warranty_period, $order->product?->warranty_period_unit);
        $type = $isWarrantyExpired ? Service::TYPE_REPAIR : Service::TYPE_WARRANTY;
        $service = Service::create(collect($request->all())->merge([
            'order_id' => $orderId,
            'type' => $type
        ])->toArray());

        if ($service) {
            return back()->with(['message' => "Thêm phiếu " . strtolower($type) . " thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    public function review($email, $id, Request $request)
    {
        $service = Service::findOrFail($id);
        if ($service->evaluate > 0) return back()->with(['error' => 'Dịch vụ đã được đánh giá, vui lòng kiểm tra lại.']);
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
