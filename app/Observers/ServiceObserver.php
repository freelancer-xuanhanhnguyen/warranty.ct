<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
use App\Notifications\NewServiceNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        $code = str_pad($service->id, 5, '0', STR_PAD_LEFT);
        $repairman_id = $service->repairman_id;
        if (!$service->repairman_id && $service->order?->product?->repairman_id) {
            $order = Order::with([
                'product:id,repairman_id',
                'product:repairman:id,status'
            ])->find($service->order_id);
            if ($order?->product?->repairman?->status) {
                $repairman_id = $order?->product?->repairman_id;
            }
        }
        $service->update(['code' => $code, 'repairman_id' => $repairman_id]);

        $service->statuses()->create([
            'code' => ServiceStatus::STATUS_WAITING
        ]);

        try {
            $type = strtolower(Service::TYPE[$service->type]);
            $user = Auth::user();
            if (request()->path() === "admin/services") {
                if ($user->role === User::ROLE_ADMIN) {
                    $users = User::active()
                        ->where(function ($q) use ($service) {
                            $q->where('role', User::ROLE_CSKH)
                                ->orWhere('id', $service->repairman_id);
                        })
                        ->get();
                } else {
                    $users = User::active()
                        ->where(function ($q) use ($service) {
                            $q->where('role', User::ROLE_ADMIN)
                                ->orWhere('id', $service->repairman_id);
                        })
                        ->get();
                }
            } else {
                $users = User::active()
                    ->where(function ($q) use ($service) {
                        $q->whereIn('role', [User::ROLE_ADMIN, User::ROLE_CSKH])
                            ->orWhere('id', $service->repairman_id);
                    })
                    ->get();
            }
            $message = "Phiếu $type mới #$service->code";

            Notification::send($users, new NewServiceNotification([
                'type' => 'new',
                'service' => $service,
                'created_by' => $user ?? request()->email,
                'message' => $message
            ]));
        } catch (\Exception $e) {
            Log::error("Notification error #$service->code: " . $e->getMessage());
        }
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        try {
            if (request()->path() === "admin/services/$service->id") {
                $type = strtolower(Service::TYPE[$service->type]);
                $user = Auth::user();
                if ($service->isDirty('repairman_id') && $service->repairman_id) {
                    $repairman = User::active()->find($service->repairman_id);
                    Notification::send($repairman, new NewServiceNotification([
                        'type' => 'update_repairman_id',
                        'service' => $service,
                        'created_by' => Auth::user(),
                        'message' => "Phiếu $type mới #$service->code"
                    ]));
                }

                if ($service->isDirty('fee_total') || $service->isDirty('fee_detail')) {
                    $users = User::where('role', User::ROLE_ADMIN)->active();
                    if ($user->role === User::ROLE_ADMIN) {
                        $users = $users->where('role', User::ROLE_ADMIN)
                            ->where('id', '!=', $user->id);
                    }
                    $users = $users->get();

                    Notification::send($users, new NewServiceNotification([
                        'type' => 'update_fee',
                        'service' => $service,
                        'created_by' => Auth::user(),
                        'message' => "Phụ phí #$service->code đã thay đổi " . format_money($service->fee_total)
                    ]));
                }
            }
        } catch (\Exception $e) {
            Log::error("Notification error #$service->code: " . $e->getMessage());
        }
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "restored" event.
     */
    public function restored(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "force deleted" event.
     */
    public function forceDeleted(Service $service): void
    {
        //
    }
}
