<?php

namespace Tests\Unit\Events;

use App\Events\FlightCancelledEvent;
use App\Listeners\SendFlightCancelledNotificationListener;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class FlightCancelledEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_send_notification_when_dispatch_event(): void
    {
        Event::fake();
        Notification::fake();

        $user = User::factory()->create();
        $flight = Flight::factory()->requested()->create(['user_id' => $user->id]);
        FlightCancelledEvent::dispatch($flight);

        Event::assertDispatched(FlightCancelledEvent::class);
        Event::assertListening(
            FlightCancelledEvent::class,
            SendFlightCancelledNotificationListener::class
        );
    }
}
