<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = \request()->q;
        $role = \request()->role;
        $query = User::when($q, function ($query) use ($q) {
            $query->where('name', 'like', "%$q%")
                ->orWhere('id', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%");
        })
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            });

        if (request()->has('export')) {
            return Excel::download(new UsersExport($query->get()), 'Nhân viên.xlsx');
        }

        $data = $query->paginate(20);
        return view('admin.users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $service = User::create(collect($request->all())->merge([
            'password' => Hash::make($request->password),
        ])->toArray());

        if ($service) {
            return back()->with(['message' => "Thêm nhân viên thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $q = \request()->q;
        $data = User::findOrFail($id);
        $services = Service::whereHas('order', function ($query) use ($id) {
            $query->select('id')
                ->where('customer_id', $id);
        })
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%$q%");
            })
            ->latest()
            ->paginate(20);

        return view('admin.users.show', compact('data', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::findOrFail($id);
        return view('admin.users.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();

        unset($data['email']);
        unset($data['password']);

        $updated = User::findOrFail($id)->update($data);

        if ($updated) {
            return back()->with(['message' => "Cập nhật nhân viên thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);
        return back()->with(['message' => 'Đã xóa thành công.']);
    }
}
