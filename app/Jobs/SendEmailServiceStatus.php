<?php

namespace App\Jobs;

use App\Mail\ServiceStatusMail;
use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailServiceStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $serviceStatus;

    /**
     * Create a new job instance.
     */
    public function __construct($serviceStatus)
    {
        $this->serviceStatus = $serviceStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = Service::with([
            'order:id,customer_id,product_id',
            'order.customer:id,email,name',
            'order.product:id,name',
        ])->find($this->serviceStatus->service_id);

        Log::info("Send mail service #$service->code to customer: " . $service?->order?->customer?->email);
        Mail::to($service?->order?->customer?->email)
            ->send(new ServiceStatusMail($service, $this->serviceStatus));
    }
}
