<?php

namespace App\Notifications;

use App\Models\Flight;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FlightCancelledNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Flight $flight)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✈️ Flight cancelled | OnFly')
            ->markdown(
                view: 'mail.flight.cancelled',
                data: [
                    'userName' => $notifiable->name,
                    'flightId' => $this->flight->id,
                    'destination' => $this->flight->destination,
                    'departuneDate' => $this->flight->departune_date,
                    'returnDate' => $this->flight->return_date,
                ]
            );;
    }
}
