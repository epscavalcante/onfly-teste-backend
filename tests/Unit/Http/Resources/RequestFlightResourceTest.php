<?php

namespace Tests\Unit\Http\Resources;

use App\Enums\FlightStatusEnum;
use App\Http\Resources\RequestFlightResource;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestFlightResourceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_validate_fields(): void
    {
        $resource = new RequestFlightResource(
            flightId: 100,
            destination: 'Belo Horizonte - MG',
            status: FlightStatusEnum::REQUESTED->value,
            departuneDate: '2025-01-10T23:00:00',
            returnDate: '2025-01-12T23:00:00',
        );
        $this->assertIsArray($resource->toArray(new Request()));
        $this->assertEquals(
            expected: [
                'id' => 100,
                'status' => 'REQUESTED',
                'destination' => 'Belo Horizonte - MG',
                'departune_date' => '2025-01-10T23:00:00',
                'return_date' => '2025-01-12T23:00:00'
            ],
            actual: $resource->toArray(new Request())
        );
    }
}
