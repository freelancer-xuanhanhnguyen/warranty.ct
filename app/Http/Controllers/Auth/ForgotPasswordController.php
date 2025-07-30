<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        if (!User::where('email', $request->email)->active()->exists()) {
            return back()->with(['error' => "Email chưa được phê duyệt."]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        switch ($status) {
            case Password::RESET_LINK_SENT:
                return back()->with(['message' => "Chúng tôi đã gửi cho bạn liên kết đặt lại mật khẩu qua email."]);

            case Password::INVALID_USER:
                return back()->with(['error' => "Email không tồn tại trong hệ thống."]);

            case Password::RESET_THROTTLED:
                return back()->with(['error' => "Bạn đã thử quá nhiều lần, vui lòng thử lại sau."]);

            default:
                return back()->withInput()->with(['error' => "Có lỗi xảy ra, vui lòng thử lại sau."]);
        }
    }
}

