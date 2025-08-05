<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // Chạy 00:00 mỗi ngày, lấy dữ liệu ngày hôm qua
        $schedule->command('sync:woo-orders')
            ->dailyAt('00:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Chạy 09:00 mỗi ngày, gửi mail thông báo cho khách hàng đến lịch bảo hành
        $schedule->command('reminder:notify')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->onOneServer();


    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
