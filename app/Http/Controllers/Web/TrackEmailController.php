<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class TrackEmailController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function trackEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        if(!Customer::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => "Bạn chưa mua thiết bị trên hệ thống."]);
        }

        return redirect(route('orders.index', $request->email));
    }
}
