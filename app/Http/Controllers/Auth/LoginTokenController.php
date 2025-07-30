<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginTokenMail;
use App\Models\Customer;
use App\Models\LoginToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginTokenController extends Controller
{
    public function login()
    {
        return view('pages.auth.login');
    }

    public function sendToken(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
        $token = Str::upper(Str::random(6));

        LoginToken::updateOrCreate(
            ['email' => $email],
            ['token' => $token, 'expires_at' => Carbon::now()->addMinutes(10)]
        );

        Mail::to($email)->queue(new LoginTokenMail($token));

        return redirect()
            ->route('customer.verifyToken')
            ->withInput()
            ->with('message', 'Vui lòng nhập mã xác thực trên email của bạn.');
    }

    public function showVerifyToken()
    {
        return view('pages.auth.verify-token');
    }

    public function verifyToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        $record = LoginToken::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record || Carbon::now()->gt($record->expires_at)) {
            return back()->with('error', 'Mã không hợp lệ hoặc đã hết hạn.');
        }

        // Tìm hoặc tạo customer
        $customer = Customer::firstWhere(
            ['email' => $request->email],
        );

        // Đăng nhập bằng guard customer
        Auth::guard('customer')->login($customer, true);

        // Xóa token
        $record->delete();

        return redirect()->route('products.index');
    }

    public function logout(Request $request)
    {
        customer()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
