<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerify;
use App\Models\User;
use App\Notifications\NewServiceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) return redirect(route('dashboard'));
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials, $request->remember ?? false)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->with([
            'error' => 'Email hoặc mật khẩu không chính xác.',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required||max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:' . implode(',', array_keys(User::ROLE)),
            'phone' => 'nullable|numeric',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:' . implode(',', array_keys(User::GENDER)),
            'address' => 'nullable|string|max:255',
        ]);


        $user = User::create(array_merge($data, [
            'password' => Hash::make($request->password),
        ]));

        SendEmailVerify::dispatch($user);

        Notification::send($user, new NewServiceNotification([
            'role' => [User::ROLE_ADMIN],
            'redirect_to' => route('admin.users.show', $user->id),
            'created_by' => null,
            'message' => "Phê duyệt tài khoản mới <strong>$user->email</strong>"
        ]));

        return redirect('/login')->with('message', 'Đăng ký tài khoản thành công, vui lòng chờ Admin phê duyệt.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

