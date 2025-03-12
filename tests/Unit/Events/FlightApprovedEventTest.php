<?php

namespace Tests\Unit\Events;

use App\Events\FlightApprovedEvent;
use App\Listeners\SendFlightApprovedNotificationListener;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class FlightApprovedEventTest extends TestCase
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
        FlightApprovedEvent::dispatch($flight);

        Event::assertDispatched(FlightApprovedEvent::class);
        Event::assertListening(
            FlightApprovedEvent::class,
            SendFlightApprovedNotificationListener::class
        );
    }
}
