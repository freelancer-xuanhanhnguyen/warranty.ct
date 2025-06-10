<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $query->where('code', 'like', "%$q%");
            });

        if (isset($status)) {
            $query = $query->whereHas('status', function ($query) use ($status) {
                $query->select('id')->where('code', $status);
            });
        }

        if (hasRole([User::ROLE_REPAIRMAN], true)) {
            $query = $query->where('repairman_id', auth()->id());
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
        $orderCodes = Order::distinct()->groupBy('code')->pluck('code');
        $users = User::where('role', User::ROLE_REPAIRMAN)->get();
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
        $service = Service::create($request->all());

        if ($service) {
            return back()->with(['message' => "Thêm phiếu " . strtolower(Service::TYPE[$request->type]) . " thành công."]);
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
        $orderCodes = Order::distinct()->groupBy('code')->pluck('code');
        $orders = Order::with('product:id,code,name')
            ->where('code', $data->order->code)
            ->get(['id', 'product_id', 'code']);

        $users = User::where('role', User::ROLE_REPAIRMAN)->get(['id', 'name']);

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
            return back()->with(['message' => "Cập nhật phiếu " . strtolower(Service::TYPE[$service->type]) . " thành công."]);
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
