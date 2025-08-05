<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the ServiceStatus "created" event.
     */
    public function saved(Product $model): void
    {
        if ($model->isDirty(['warranty_period_unit', 'warranty_period', 'periodic_warranty_unit', 'periodic_warranty'])) {
            foreach ($model->orders as $order) {
                $status = checkWarrantyStatus($order, $model);

                $order->updateQuietly([
                    'end_date' => $status['end_date'],
                    'next_date' => $status['next_date'],
                    'old_date' => $status['old_date'],
                ]);
            }
        }

    }
}
