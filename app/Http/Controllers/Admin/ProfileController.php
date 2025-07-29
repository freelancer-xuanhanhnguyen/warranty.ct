<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $data = Auth::user();
        return view('admin.profile.index', compact('data'));
    }

    public function update($id, Request $request)
    {
        $data = $request->validate([
            'name' => 'required||max:255',
            'role' => 'required|in:' . implode(',', array_keys(User::ROLE)),
            'phone' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:' . implode(',', array_keys(User::GENDER)),
            'address' => 'nullable|string|max:255',
        ]);

        $updated = User::findOrFail($id)->update($data);

        if ($updated) {
            return back()->with(['message' => "Cập nhật hồ sơ thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }
}
