<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if(!Customer::where('email', $request->email)->exists()) {
            return redirect('/')->withErrors(['email' => "Bạn chưa mua thiết bị trên hệ thống."]);
        }

        return $next($request);
    }
}
