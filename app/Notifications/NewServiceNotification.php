<?php

namespace App\Notifications;

use App\Mail\NewServiceMail;
use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewServiceNotification extends Notification
{
    use Queueable;

    protected Service $service;

    /**
     * Create a new notification instance.
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new NewServiceMail($this->service))
            ->to($notifiable->email);
    }

    public function toDatabase($notifiable)
    {
        $type = strtolower(Service::TYPE[$this->service->type]);

        return [
            'service_id' => $this->service->id,
            'message' => "Phiếu $type mới #" . $this->service->code,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
