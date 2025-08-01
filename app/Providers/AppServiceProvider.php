<?php

namespace App\Providers;

use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.backend', function ($view) {
            $user = Auth::user();
            $unreadNotifications = $user->unreadNotifications()->exists();
            $notifications = $user
                ->notifications()
                ->whereJsonContains('data->role', $user->role)
                ->get();

            if ($user->role !== User::ROLE_REPAIRMAN) {
                $ids = array_column($notifications->toArray(), 'id');

                $serviceNotifications = DatabaseNotification::whereNotIn('id', $ids)
                    ->whereJsonContains('data->role', $user->role)
                    ->get();

                $notifications = collect($notifications->toArray())
                    ->merge($serviceNotifications)
                    ->sortByDesc('created_at');

                $unreadNotifications = (bool)$notifications->whereNull('read_at')->count();
                $notifications = $notifications->values();
            }

            $view->with(compact('unreadNotifications', 'notifications'));
        });
    }
}
