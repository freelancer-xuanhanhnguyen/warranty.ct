<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->markAsRead();

        if ($request->redirect_url) return redirect($request->redirect_url);

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $ids = $user
            ->notifications()
            ->whereJsonContains('data->role', $user->role)
            ->pluck('id')
            ->toArray();

        if ($user->role !== User::ROLE_REPAIRMAN) {
            $serviceIds = DatabaseNotification::whereNotIn('id', $ids)
                ->whereJsonContains('data->role', $user->role)
                ->pluck('id')
                ->toArray();
            $ids = array_merge($ids, $serviceIds);
        }

        DatabaseNotification::whereIn('id', $ids)
            ->get()
            ->markAsRead();

        return back();
    }
}
