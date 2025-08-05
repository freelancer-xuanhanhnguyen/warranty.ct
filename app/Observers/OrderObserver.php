<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the ServiceStatus "created" event.
     */
    public function saved(Order $model): void
    {
        $status = checkWarrantyStatus($model);

        $model->updateQuietly([
            'end_date' => $status['end_date'],
            'next_date' => $status['next_date'],
            'old_date' => $status['old_date'],
        ]);
    }
}
