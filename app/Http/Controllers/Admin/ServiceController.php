<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ServicesExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%{$q}%");
            });

        if (isset($status)) {
            $query = $query->whereHas('status', function ($query) use ($status) {
                $query->select('id')->where('code', $status);
            });
        }

        if (hasRole([User::ROLE_REPAIRMAN], true)) {
            $query = $query->where('repairman_id', auth()->id());
        }

        if (request()->has('export')) {
            return Excel::download(new ServicesExport($query->latest()->get()), 'Phiếu yêu cầu bảo hành - sửa chữa.xlsx');
        }

        $data = $query
            ->latest()
            ->paginate(20);

        return view('admin.services.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orderCodes = Order::with('customer:id,name')
            ->distinct()
            ->groupBy(['code', 'customer_id'])
            ->get(['code', 'customer_id']);

        $users = User::where('role', User::ROLE_REPAIRMAN)->active()->get(['id', 'name']);
        $orders = null;
        if (\request()->has('order_code'))
            $orders = Order::with('product:id,code,name')
                ->where('code', \request()->order_code)
                ->get(['id', 'product_id', 'code']);

        return view('admin.services.create', compact('orderCodes', 'users', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $service = Service::with('status')
            ->where('order_id', $request->order_id)
            ->whereHas('status', function ($q) {
                $q->whereNotIn('code', [ServiceStatus::STATUS_COMPLETED, ServiceStatus::STATUS_CANCELED]);
            })->first('id');

        if ($service) return redirect(route('admin.services.show', $service->id))
            ->with(['error' => 'Sản phẩm đang trong quá trình bảo hành - sữa chữa.']);

        $service = Service::create($request->all());

        if ($service) {
            return redirect(route('admin.services.show', $service->id))
                ->with(['message' => "Thêm phiếu " . strtolower(Service::TYPE[$request->type]) . " thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Service::with([
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])->findOrFail($id);
        return view('admin.services.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Service::with(['order:id,code', 'status', 'statuses'])->findOrFail($id);
        $orderCodes = Order::with('customer:id,name')
            ->distinct()
            ->groupBy(['code', 'customer_id'])
            ->get(['code', 'customer_id']);

        $orders = Order::with('product:id,code,name')
            ->where('code', $data->order->code)
            ->get(['id', 'product_id', 'code']);

        $users = User::where('role', User::ROLE_REPAIRMAN)->active()->get(['id', 'name']);

        if (!$data) abort(404);
        return view('admin.services.edit', compact('data', 'orderCodes', 'orders', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::with(['status'])->find($id);
        if (!$service) abort(404);

        $updated = $service?->update(collect($request->all())->merge([
            'evaluate' => $request->score ?: null,
            'evaluate_note' => $request->score ? $request->evaluate_note : null
        ])->toArray());

        if ($service->status?->code != $request->status) {
            $service->statuses()->create([
                'code' => $request->status
            ]);
        }

        if ($updated) {
            return redirect(route('admin.services.show', $id))
                ->with(['message' => "Cập nhật phiếu " . strtolower(Service::TYPE[$service->type]) . " thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Service::destroy($id);
        return back()->with(['message' => 'Đã xóa thành công.']);
    }
}
