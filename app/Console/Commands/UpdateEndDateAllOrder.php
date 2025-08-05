<?php

namespace App\Console\Commands;

use App\Mail\ReminderWarrantyProductMail;
use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class UpdateEndDateAllOrder extends Command
{
    protected $signature = 'update:orders {--all}';
    protected $description = 'Cập nhật lại end date all order';

    public function handle()
    {

        if (!$this->option('all')) {
            $yesterday = now()->subDay()->toDateString();
            $orders = Order::with(['customer', 'product'])->whereDate('next_date', $yesterday)->get();

            $this->info("Ngày cập nhật: $yesterday");
        } else {
            $orders = Order::with(['product'])->get();
        }

        foreach ($orders as $order) {
            $status = checkWarrantyStatus($order);
            $order->updateQuietly([
                'end_date' => $status['end_date'],
                'next_date' => $status['next_date'],
                'old_date' => $status['old_date'],
            ]);
        }
        $this->info(count($orders) . ' success');
        return 0;
    }
}
