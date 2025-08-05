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
                    Notification::send($service, new NewServiceNotification([
                        'role' => [User::ROLE_CSKH],
                        'redirect_to' => route('admin.services.show', $service->id),
                        'created_by' => Auth::user(),
                        'message' => "Admin đã tạo phiếu $type mới <strong>$service->code</strong>"
                    ]));
                } else {
                    Notification::send($service, new NewServiceNotification([
                        'role' => [User::ROLE_ADMIN],
                        'redirect_to' => route('admin.services.show', $service->id),
                        'created_by' => Auth::user(),
                        'message' => "CSKH đã tạo phiếu $type mới <strong>$service->code</strong>"
                    ]));
                }
            } else {
                Notification::send($service, new NewServiceNotification([
                    'role' => [User::ROLE_ADMIN, User::ROLE_CSKH],
                    'redirect_to' => route('admin.services.show', $service->id),
                    'created_by' => customer()->user(),
                    'message' => "Khách hàng đã tạo phiếu $type mới <strong>$service->code</strong>"
                ]));
            }

            if ($repairman_id) {
                $repairman = User::active()
                    ->find($service->repairman_id);

                if ($repairman)
                    Notification::send($repairman, new NewServiceNotification([
                        'role' => [User::ROLE_REPAIRMAN],
                        'redirect_to' => route('admin.services.show', $service->id),
                        'created_by' => Auth::user(),
                        'message' => "Bạn được gán phiếu $type mới <strong>$service->code</strong>"
                    ]));
            }
        } catch (\Exception $e) {
            Log::error("Notification error $service->code: " . $e->getMessage());
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

                    $oldRepairman = User::active()->find($service->getOriginal('repairman_id'));

                    if ($oldRepairman) {
                        Notification::send($oldRepairman, new NewServiceNotification([
                            'role' => [User::ROLE_REPAIRMAN],
                            'redirect_to' => route('admin.services.show', $service->id),
                            'created_by' => $user,
                            'message' => "Bạn bị gỡ khỏi phiếu $type <strong>$service->code</strong>"
                        ]));
                    }

                    $repairman = User::active()->find($service->repairman_id);

                    if ($repairman)
                        Notification::send($repairman, new NewServiceNotification([
                            'role' => [User::ROLE_REPAIRMAN],
                            'redirect_to' => route('admin.services.show', $service->id),
                            'created_by' => $user,
                            'message' => "Bạn được gán phiếu $type mới <strong>$service->code</strong>"
                        ]));
                }

                if ($service->isDirty('fee_total')) {
                    $role = array_values(array_diff([User::ROLE_ADMIN, User::ROLE_CSKH], [$user->role]));

                    Notification::send($service, new NewServiceNotification([
                        'role' => $role,
                        'redirect_to' => route('admin.services.show', $service->id),
                        'created_by' => $user,
                        'message' => "Phiếu <strong>$service->code</strong> đã thay đổi phụ phí <strong>" . format_money($service->fee_total) . "</strong>"
                    ]));
                }
            }
        } catch (\Exception $e) {
            Log::error("Notification error $service->code: " . $e->getMessage());
        }
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        $user = auth()->user();
        $type = strtolower(Service::TYPE[$service->type]);

        Notification::send($service, new NewServiceNotification([
            'role' => array_values(array_diff([User::ROLE_ADMIN, User::ROLE_CSKH], $user->role)),
            'redirect_to' => route('admin.services.show', $service->id),
            'created_by' => $user,
            'message' => "Phiếu $type <strong>$service->code</strong> đã bị xóa"
        ]));
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
