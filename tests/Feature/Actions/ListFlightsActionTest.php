<?php

namespace Tests\Feature\Actions;

use App\Actions\ListFLights\ListFlightsAction;
use App\Actions\ListFLights\ListFlightsActionInput;
use App\Actions\ListFLights\ListFlightsActionOutput;
use App\Enums\FlightStatusEnum;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListFlightsActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_flights_filtering_by_user(): void
    {
        Flight::factory()
            ->for(User::factory())
            ->requested()
            ->count(2)
            ->create();

        Flight::factory()
            ->for(User::factory())
            ->approved()
            ->count(3)
            ->create();

        $input = new ListFlightsActionInput(
            userId: 1
        );
        $output = ListFlightsAction::handle($input);
        $this->assertInstanceOf(ListFlightsActionOutput::class, $output);
        $this->assertEquals(2, count($output->items));
    }

    public function test_list_flights_filtering_by_status(): void
    {
        Flight::factory()
            ->for(User::factory())
            ->requested()
            ->count(2)
            ->create();

        Flight::factory()
            ->for(User::factory())
            ->approved()
            ->count(3)
            ->create();

        Flight::factory()
            ->for(User::factory())
            ->canceled()
            ->count(5)
            ->create();

        $input = new ListFlightsActionInput(
            status: FlightStatusEnum::CANCELED->value
        );
        $output = ListFlightsAction::handle($input);
        $this->assertInstanceOf(ListFlightsActionOutput::class, $output);
        $this->assertEquals(5, count($output->items));
    }

    public function test_list_flights_filtering_by_period(): void
    {
        Flight::factory()
            ->for(User::factory())
            ->create([
                'departune_date' => '2025-01-01T23:00:00',
                'return_date' => '2025-01-03T15:00:00'
            ]);

        Flight::factory()
            ->for(User::factory())
            ->create([
                'departune_date' => '2025-01-01T06:00:00',
                'return_date' => '2025-01-05T15:00:00'
            ]);

        Flight::factory()
            ->for(User::factory())
            ->create([
                'departune_date' => '2025-01-06T07:00:00',
                'return_date' => '2025-01-07T13:00:00'
            ]);

        Flight::factory()
            ->for(User::factory())
            ->create([
                'departune_date' => '2025-03-01T11:00:00',
                'return_date' => '2025-03-10T12:00:00'
            ]);

        Flight::factory()
            ->for(User::factory())
            ->create([
                'departune_date' => '2025-02-05T08:00:00',
                'return_date' => '2025-02-05T23:30:00'
            ]);

        $inputAllFlightsByYear = new ListFlightsActionInput(
            startDate: '2025-01-01T00:00:01',
            endDate: '2025-12-31T23:59:59',
        );
        $outputAllFlightByYear = ListFlightsAction::handle($inputAllFlightsByYear);
        $this->assertInstanceOf(ListFlightsActionOutput::class, $outputAllFlightByYear);
        $this->assertEquals(5, count($outputAllFlightByYear->items));

        $inputAllFlightsByMonth = new ListFlightsActionInput(
            startDate: '2025-01-01T00:00:01',
            endDate: '2025-01-31T23:59:59',
        );
        $outputAllFlightByMonth = ListFlightsAction::handle($inputAllFlightsByMonth);
        $this->assertInstanceOf(ListFlightsActionOutput::class, $outputAllFlightByMonth);
        $this->assertEquals(3, count($outputAllFlightByMonth->items));
    }

    public function test_list_flights_filtering_by_user_status_and_period(): void
    {

        User::factory()
            ->has(Flight::factory()->requested()->state([
                'departune_date' => '2025-01-01T23:00:00',
                'return_date' => '2025-01-03T15:00:00'
            ]))
            ->has(Flight::factory()->approved()->state([
                'departune_date' => '2025-01-01T23:00:00',
                'return_date' => '2025-01-03T15:00:00'
            ]))
            ->has(Flight::factory()->canceled()->state([
                'departune_date' => '2025-01-01T23:00:00',
                'return_date' => '2025-01-03T15:00:00'
            ]))
            ->create();

        User::factory()
            ->has(Flight::factory()->requested()->state([
                'departune_date' => '2025-03-01T11:00:00',
                'return_date' => '2025-03-10T12:00:00'
            ]))
            ->has(Flight::factory()->canceled()->state([
                'departune_date' => '2025-02-05T08:00:00',
                'return_date' => '2025-02-05T23:30:00'
            ]))
            ->create();

        $input = new ListFlightsActionInput(
            userId: 2,
            status: FlightStatusEnum::CANCELED->value,
            startDate: '2025-02-01T00:00:01',
            endDate: '2025-02-31T23:59:59',
        );

        $output = ListFlightsAction::handle($input);
        $this->assertInstanceOf(ListFlightsActionOutput::class, $output);
        $this->assertEquals(1, count($output->items));
    }
}
