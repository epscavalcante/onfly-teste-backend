<?php

namespace App\Listeners;

use App\Events\FlightRequestedEvent;
use App\Notifications\FlightRequestedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFlightRequestedNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FlightRequestedEvent $event): void
    {
        $event->flight->load('user');

        $event->flight->user->notify(new FlightRequestedNotification($event->flight));
    }
}
