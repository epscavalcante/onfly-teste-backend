<?php

namespace App\Listeners;

use App\Events\FlightRequestedEvent;
use App\Notifications\FlightRequestedNotification;

class SendFlightRequestedNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(FlightRequestedEvent $event): void
    {
        $event->flight->load('user');

        $event->flight->user->notify(new FlightRequestedNotification($event->flight));
    }
}
