<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ServicesExport;
use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\Activity;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                $q = escape_like($q);
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

        if (request()->has('export')) {
            return Excel::download(new ServicesExport($query->latest()->get()), 'Phiếu yêu cầu bảo hành - sửa chữa.xlsx');
        }

        $data = $query->selectRaw('services.*')
            ->paginate(20);

        return view('admin.services.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->role === User::ROLE_REPAIRMAN) abort('404');
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

        $accessories = Accessory::where('quantity', '>', 0)->get();

        return view('admin.services.create', compact('orderCodes', 'users', 'orders', 'accessories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role === User::ROLE_REPAIRMAN) abort('404');
        $data = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'repairman_id' => 'nullable|exists:users,id',
            'code' => 'nullable|string|max:255',
            'type' => 'nullable|in:' . implode(',', array_keys(Service::TYPE)),
            'content' => 'nullable|string|max:500',
            'fee_total' => 'nullable|numeric|max:999999999999999999999',
            'note' => 'nullable|string|max:500',
            'reception_date' => 'nullable|date',
            'expected_completion_date' => 'nullable|date',
            'evaluate' => 'nullable|numeric|min:1|max:5',
            'evaluate_note' => 'nullable|string|max:500',
        ]);

        try {
            $service = Service::with('status')
                ->where('order_id', $request->order_id)
                ->whereHas('status', function ($q) {
                    $q->whereNotIn('code', [ServiceStatus::STATUS_COMPLETED, ServiceStatus::STATUS_CANCELED]);
                })->first('id');

            if ($service) return redirect(route('admin.services.show', $service->id))
                ->with(['error' => 'Sản phẩm đang trong quá trình bảo hành - sữa chữa.']);

            DB::beginTransaction();

            $service = Service::create($data);

            if ($service) {
                if (isset($request->accessories)) {
                    $ids = $request->accessories['id'] ?? [];
                    if (count($ids)) {
                        $quantity = $request->accessories['quantity'] ?? [];
                        $total = $request->accessories['total'] ?? [];
                        $items = [];
                        foreach ($ids as $key => $accessoryId) {
                            $items[] = [
                                'accessory_id' => $accessoryId,
                                'quantity' => $quantity[$key],
                                'total' => $total[$key]
                            ];
                        }
                        $service->items()->createMany($items);
                    }
                }

                DB::commit();

                return redirect(route('admin.services.show', $service->id))
                    ->with(['message' => "Thêm phiếu " . strtolower(Service::TYPE[$request->type]) . " thành công."]);
            }
            throw new \Exception();

        } catch (\Exception $exception) {
            Log::debug($exception);
            DB::rollBack();

            return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = Service::with([
            'order.product',
            'order.customer',
            'repairman:id,name',
            'status',
        ]);

        if (auth()->user()->role === User::ROLE_REPAIRMAN) {
            $query = $query->where('repairman_id', auth()->id());
        }

        $data = $query->findOrFail($id);


        $logs = Activity::inLog('services')
            ->with(['causer:id,name', 'repairman:id,name'])
            ->latest()
            ->get();

        return view('admin.services.show', compact('data', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (auth()->user()->role === User::ROLE_REPAIRMAN) abort('404');
        $data = Service::with(['order:id,code', 'status', 'statuses', 'items'])->findOrFail($id);

        $orderCodes = Order::with('customer:id,name')
            ->distinct()
            ->groupBy(['code', 'customer_id'])
            ->get(['code', 'customer_id']);

        $orders = Order::with('product:id,code,name')
            ->where('code', $data->order->code)
            ->get(['id', 'product_id', 'code']);

        $users = User::where('role', User::ROLE_REPAIRMAN)->active()->get(['id', 'name']);

        $accessories = Accessory::where('quantity', '>', 0)->get();

        if (!$data) abort(404);
        return view('admin.services.edit', compact('data', 'orderCodes', 'orders', 'users', 'accessories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->user()->role === User::ROLE_REPAIRMAN) abort('404');
        $data = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'repairman_id' => 'nullable|exists:users,id',
            'code' => 'nullable|string|max:255',
            'type' => 'nullable|in:' . implode(',', array_keys(Service::TYPE)),
            'content' => 'nullable|string|max:500',
            'fee_total' => 'nullable|numeric|max:99999999999999999',
            'note' => 'nullable|string|max:500',
            'reception_date' => 'nullable|date',
            'expected_completion_date' => 'nullable|date',
            'evaluate' => 'nullable|numeric|min:1|max:5',
            'evaluate_note' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            $service = Service::with(['status'])->findOrFail($id);

            $updated = $service?->update(collect($data)->merge([
                'evaluate' => $request->score ?: null,
                'evaluate_note' => $request->score ? $request->evaluate_note : null
            ])->toArray());

            if ($updated) {
                if ($service->status?->code != $request->status) {
                    $service->statuses()->create([
                        'code' => $request->status
                    ]);
                }

                if (isset($request->accessories)) {
                    $ids = $request->accessories['id'] ?? [];
                    if (count($ids)) {
                        Accessory::whereNotIn('id', $ids)->delete();
                        $quantity = $request->accessories['quantity'] ?? [];
                        $total = $request->accessories['total'] ?? [];
                        foreach ($ids as $key => $accessoryId) {
                            $item = [
                                'quantity' => $quantity[$key],
                                'total' => $total[$key]
                            ];
                            $service->items()->updateOrCreate(['accessory_id' => $accessoryId], $item);
                        }
                    }
                }

                DB::commit();

                return redirect(route('admin.services.show', $id))
                    ->with(['message' => "Cập nhật phiếu " . strtolower(Service::TYPE[$service->type]) . " thành công."]);
            }
            throw new \Exception();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::debug($exception);
            return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
        }
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
