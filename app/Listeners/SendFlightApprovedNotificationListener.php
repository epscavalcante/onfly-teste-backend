<?php

namespace App\Listeners;

use App\Events\FlightApprovedEvent;
use App\Notifications\FlightApprovedNotification;

class SendFlightApprovedNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(FlightApprovedEvent $event): void
    {
        $event->flight->load('user');

        $event->flight->user->notify(new FlightApprovedNotification($event->flight));
    }
}
