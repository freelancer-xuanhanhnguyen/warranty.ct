<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()
            ->unreadNotifications()
            ->findOrFail($id);

        $notification->markAsRead();

        if ($request->redirect_url) return redirect($request->redirect_url);

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()
            ->unreadNotifications
            ->markAsRead(); // hoặc update(['read_at'=>now()])

        return back();
    }
}
