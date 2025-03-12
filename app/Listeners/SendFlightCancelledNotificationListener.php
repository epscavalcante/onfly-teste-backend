<?php

namespace App\Listeners;

use App\Events\FlightCancelledEvent;
use App\Notifications\FlightCancelledNotification;

class SendFlightCancelledNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(FlightCancelledEvent $event): void
    {
        $event->flight->load('user');

        $event->flight->user->notify(new FlightCancelledNotification($event->flight));
    }
}
