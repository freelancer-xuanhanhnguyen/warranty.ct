<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = \request()->q;
        $query = Order::with([
            'product.repairman:id,name,email',
            'customer:id,code,name,email',
        ])
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%{$q}%")
                    ->orWhereHas('product', function ($_query) use ($q) {
                        $_query->where('name', 'like', "%{$q}%")
                            ->orWhere('code', 'like', "%{$q}%");
                    });
            });

        $sort = \request()->sort ?? [];
        foreach ($sort as $key => $value) {
            $query = $query->join('products', 'products.id', '=', 'orders.product_id')
                ->orderBy(str_replace('__', '.', $key), $value);
        }

        if (request()->has('export')) {
            return Excel::download(new OrdersExport($query->get()), 'Thiết bị bảo hành - sửa chữa.xlsx');
        }
        $data = $query->paginate(20);

        return view('admin.products.index', compact('data'));
    }

    public function history($id)
    {
        $data = Order::findOrFail($id);

        $q = \request()->q;
        $status = \request()->status;
        $query = Service::with([
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])
            ->whereHas('order', function ($query) use ($id) {
                $query->select('id')
                    ->where('product_id', $id);
            })
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%{$q}%");
            });

        if (isset($status)) {
            $query = $query->whereHas('status', function ($query) use ($status) {
                $query->select('id')->where('code', $status);
            });
        }

        $services = $query
            ->latest()
            ->paginate(20);

        return view('admin.products.show', compact('data', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Order::with('product:id,code,name')
            ->where('code', $id)
            ->get(['id', 'product_id', 'code']);

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Product::findOrFail($id);
        $users = User::where('role', User::ROLE_REPAIRMAN)->get(['id', 'name']);

        return view('admin.products.edit', compact('data', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'serial' => 'nullable|string|max:255',
            'warranty_period_unit' => 'nullable|in:' . implode(',', array_keys(Product::WARRANTY_UNIT)),
            'warranty_period' => 'nullable|numeric|max:99999',
            'periodic_warranty_unit' => 'nullable|in' . implode(',', array_keys(Product::WARRANTY_UNIT)),
            'periodic_warranty' => 'nullable|numeric|max:99999',
            'repairman_id' => 'nullable|exists:users,id',
        ]);

        $updated = Product::findOrFail($id)->update($data);

        if ($updated) {
            return back()
                ->with(['message' => "Cập thông tin sản phâm thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
