<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Beautician;

class BookingStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = ucfirst($this->booking->status);
        $message = "";

        if ($notifiable instanceof Client) {
             $message = "Your booking for {$this->booking->service->service_name} has been {$this->booking->status}.";
        } elseif ($notifiable instanceof Beautician) {
             $clientName = $this->booking->client ? $this->booking->client->name : 'Client';
             $message = "Booking with {$clientName} for {$this->booking->service->service_name} has been {$this->booking->status}.";
        }

        return [
            'booking_id' => $this->booking->id,
            'service_name' => $this->booking->service->service_name,
            'status' => $this->booking->status,
            'message' => $message,
            'type' => 'status_update'
        ];
    }
}
