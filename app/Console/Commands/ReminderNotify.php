<?php

namespace App\Console\Commands;

use App\Mail\ReminderWarrantyProductMail;
use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class ReminderNotify extends Command
{
    protected $signature = 'reminder:notify';
    protected $description = 'Nhắc nhở khách hàng đến lịch bảo hành sản phẩm';

    public function handle()
    {
        $date = now()->addDay()->toDateString();
        $orders = Order::with(['customer', 'product'])->whereDate('next_date', $date)->get();

        foreach ($orders as $order) {
            Mail::to($order->customer->email)->queue(new ReminderWarrantyProductMail($order));
        }
        return 0;
    }
}
