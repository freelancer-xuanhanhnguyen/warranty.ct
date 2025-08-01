<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AccessoriesExport;
use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AccessoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = \request()->q;
        $query = Accessory::when($q, function ($query) use ($q) {
            $q = escape_like($q);
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('code', 'like', "%{$q}%");
        });

        $sort = \request()->sort ?? [];
        foreach ($sort as $key => $value) {
            $query = $query->orderBy(str_replace('__', '.', $key), $value);
        }

        if (request()->has('export')) {
            return Excel::download(new AccessoriesExport($query->get()), 'linh kiện.xlsx');
        }

        $data = $query->paginate(20);
        return view('admin.accessories.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.accessories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'code' => 'nullable|max:20|unique:accessories',
            'quantity' => 'required|numeric|max:99999',
            'unit_price' => 'required|numeric|min:0|max:9999999999',
        ]);

        $service = Accessory::create($data);

        if ($service) {
            return back()->with(['message' => "Thêm linh kiện thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Accessory::findOrFail($id);
        return view('admin.accessories.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'code' => 'nullable|max:20|unique:accessories',
            'quantity' => 'required|numeric|max:99999',
            'unit_price' => 'required|numeric|min:0|max:9999999999',
        ]);

        $updated = Accessory::findOrFail($id)->update($data);

        if ($updated) {
            return back()->with(['message' => "Cập nhật linh kiện thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Accessory::findOrFail($id)->delete();

        return back()->with(['message' => 'Đã xóa thành công.']);
    }
}
