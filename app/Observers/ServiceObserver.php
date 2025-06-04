<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\ServiceStatus;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        $code = str_pad($service->id, 5, '0', STR_PAD_LEFT);
        $service->update(['code' => $code, 'repairman_id' => $service->repairman_id ?? $service->order?->product?->repairman_id]);

        $service->statuses()->create([
            'code' => ServiceStatus::STATUS_WAITING
        ]);
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        //
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
