<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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

        return redirect(route('orders.index', $request->email));
    }
}
