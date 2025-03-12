<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FlightControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_request_flight_returns_unauthorized_response(): void
    {
        $response = $this->postJson(route('flights.store'));
        $response->assertUnauthorized();
    }

    public function test_approve_flight_returns_unauthorized_response(): void
    {
        $response = $this->postJson(route('flights.approve', 1));
        $response->assertUnauthorized();
    }

    public function test_cancel_flight_returns_unauthorized_response(): void
    {
        $response = $this->postJson(route('flights.cancel', 1));
        $response->assertUnauthorized();
    }

    public function test_request_flight_returns_unprocessable_entity_response(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('flights.store'));
        $response->assertUnprocessable();
        $response->assertInvalid([
            'destination' => ['The destination field is required.'],
            'departune_date' => ['The departune date field is required.'],
            'return_date' => ['The return date field is required.'],
        ]);
    }

    public function test_request_flight_returns_not_found_response(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('flights.approve', 1));
        $response->assertNotFound();
    }

    public function test_approve_flight_returns_forbidden_response(): void
    {
        $user = User::factory()->create();
        $flight = Flight::factory()->for($user)->create();
        $response = $this->actingAs($user)->postJson(route('flights.approve', $flight->id));
        $response->assertForbidden();
    }

    public function test_cancel_flight_returns_forbidden_response(): void
    {
        $user = User::factory()->create();
        $flightApproved = Flight::factory()->for($user)->approved()->create();
        $responseFlightApproved = $this->actingAs($user)->postJson(route('flights.cancel', $flightApproved->id));
        $responseFlightApproved->assertForbidden();

        $flightCancelled = Flight::factory()->for($user)->canceled()->create();
        $responseFlightCancelle = $this->actingAs($user)->postJson(route('flights.cancel', $flightCancelled->id));
        $responseFlightCancelle->assertForbidden();
    }

    public function test_request_flight_returns_successfull_response(): void
    {
        $user = User::factory()->create();

        $date = now()->addDays(rand(2, 5));
        $departuneDate = $date->copy()->addDay()->setHour(10);
        $returnDate = $departuneDate->copy()->addDays(rand(1, 3))->setHour(21);

        $response = $this->actingAs($user)->postJson(
            uri: route('flights.store'),
            data: [
                'destination' => 'Belo Horizonte - MG',
                'departune_date' => $departuneDate->format('Y-m-d H:i'),
                'return_date' => $returnDate->format('Y-m-d H:i')
            ]
        );
        $response->assertSuccessful();
    }

    public function test_approve_flight_returns_successfull_response(): void
    {
        $user = User::factory()->create();
        $flight = Flight::factory()->for(
            factory: User::factory()->create()
        )->requested()->create();

        $response = $this->actingAs($user)->postJson(
            uri: route('flights.approve', $flight->id),
        );

        $response->assertNoContent();
    }

    public function test_cancel_requested_flight_returns_successfull_response(): void
    {
        $user = User::factory()->create();
        $flight = Flight::factory()->for(
            factory: $user
        )->requested()->create();

        $response = $this->actingAs($user)->postJson(
            uri: route('flights.cancel', $flight->id),
        );

        $response->assertNoContent();
    }
}
