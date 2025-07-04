<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('admin.change-password.index');
    }

    public function update($id, Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail('Mật khẩu hiện tại không đúng.');
                }
            }],
            'password' => 'required|min:6|confirmed',
        ]);

        $updated = User::findOrFail($id)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        if ($updated) {
            return back()->with(['message' => "Đổi mật khẩu thành công."]);
        }

        return back()->withInput()->with(['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
    }
}
