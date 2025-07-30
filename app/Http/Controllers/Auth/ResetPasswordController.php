<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!User::where('email', $request->email)->active()->exists()) {
            return back()->with(['error' => "Email chưa được phê duyệt."]);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
                Auth::login($user);
            }
        );

        switch ($status) {
            case Password::PASSWORD_RESET:
                return back()->with(['message' => "Thay đổi mật khẩu thành công."]);

            case Password::INVALID_USER:
                return back()->with(['error' => "Email không tồn tại trong hệ thống."]);

            case Password::INVALID_TOKEN:
                return back()->with(['error' => "Mã xác thực không hợp lệ."]);

            case Password::RESET_THROTTLED:
                return back()->with(['error' => "Bạn đã thử quá nhiều lần, vui lòng thử lại sau."]);

            default:
                return back()->withInput()->with(['error' => "Có lỗi xảy ra, vui lòng thử lại sau."]);
        }
    }
}

