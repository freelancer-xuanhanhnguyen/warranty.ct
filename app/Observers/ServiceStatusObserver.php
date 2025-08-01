<?php

namespace App\Observers;

use App\Jobs\SendEmailServiceStatus;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
use App\Notifications\NewServiceNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ServiceStatusObserver
{
    /**
     * Handle the ServiceStatus "created" event.
     */
    public function created(ServiceStatus $serviceStatus): void
    {
        SendEmailServiceStatus::dispatch($serviceStatus);

        $service = Service::query()->find($serviceStatus->service_id, ['id', 'type', 'code']);
        try {
            $user = Auth::user();
            $type = strtolower(Service::TYPE[$service->type]);

            $role = array_values(array_diff([User::ROLE_ADMIN, User::ROLE_CSKH], [$user->role]));
            $to = $service;
            if($user->role === User::ROLE_REPAIRMAN) {
                $role = [User::ROLE_REPAIRMAN];
                $to = $user;
            }

            Notification::send($to, new NewServiceNotification([
                'role' => $role,
                'redirect_to' => route('admin.services.show', $serviceStatus->service_id),
                'created_by' => $user,
                'message' => "Phiếu $type <strong>$service->code</strong> đã thay đổi trạng thái <strong>" . ServiceStatus::STATUS[$serviceStatus->code] . "</strong>"
            ]));
        } catch (\Exception $e) {
            Log::error("Notification error $service->code: " . $e->getMessage());
        }
    }
}
