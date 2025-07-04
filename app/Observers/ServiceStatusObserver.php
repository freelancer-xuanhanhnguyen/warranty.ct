<?php

namespace App\Observers;

use App\Mail\ServiceStatusMail;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
use App\Notifications\NewServiceNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ServiceStatusObserver
{
    /**
     * Handle the ServiceStatus "created" event.
     */
    public function created(ServiceStatus $serviceStatus): void
    {
        try {
            $service = Service::with([
                'order:id,customer_id,product_id',
                'order.customer:id,email,name',
                'order.product:id,name',
            ])->find($serviceStatus->service_id);

            Mail::to($service?->order?->customer?->email)
                ->send(new ServiceStatusMail($service, $serviceStatus));

            Log::info("Send mail service #$service->code to customer: " . $service?->order?->customer?->email);

            try {
                if ($serviceStatus->code == ServiceStatus::STATUS_COMPLETED) {
                    $user = Auth::user();

                    if ($user->role != User::ROLE_CSKH) {
                        $type = strtolower(Service::TYPE[$service->type]);
                        $users = User::where('role', User::ROLE_CSKH)->active()->get();
                        Notification::send($users, new NewServiceNotification([
                            'type' => 'completed',
                            'services' => $service,
                            'created_by' => $user,
                            'message' => "Phiếu #$service->code đã $type xong."
                        ]));
                    }
                }
            } catch (\Exception $e) {
                Log::error("Notification error #$service->code: " . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error("Send mail service #$service->code to customer: " . $e->getMessage());
        }
    }
}
