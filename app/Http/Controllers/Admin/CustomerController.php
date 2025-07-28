<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = \request()->q;
        $query = Customer::when($q, function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('code', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%");
        });

        if (request()->has('export')) {
            return Excel::download(new CustomersExport($query->get()), 'Khách hàng.xlsx');
        }

        $data = $query->paginate(20);
        return view('admin.customers.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:customers,email',
            'gender' => 'nullable|integer|in:' . implode(',', array_keys(Customer::GENDER)),
            'phone' => 'nullable|string|max:20',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        $service = Customer::create($request->all());

        if ($service) {
            return back()->with(['message' => "Thêm khách hàng thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $q = \request()->q;
        $status = \request()->status;

        $data = Customer::findOrFail($id);
        $query = Service::with([
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])
            ->whereHas('order', function ($query) use ($id) {
                $query->select('id')
                    ->where('customer_id', $id);
            })
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%{$q}%");
            });

        if (isset($status)) {
            $query = $query->whereHas('status', function ($query) use ($status) {
                $query->select('id')->where('code', $status);
            });
        }

        $services = $query->latest()
            ->paginate(20);

        return view('admin.customers.show', compact('data', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required|email|unique:customers,email,' . $id,
        ]);

        $updated = Customer::findOrFail($id)->update($request->all());

        if ($updated) {
            return back()->with(['message' => "Cập nhật khách hàng thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Customer::destroy($id);
        return back()->with(['message' => 'Đã xóa thành công.']);
    }
}
