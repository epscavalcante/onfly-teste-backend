<?php

namespace Tests\Unit\Http\Resources;

use App\Enums\FlightStatusEnum;
use App\Http\Resources\GetFlightDetailResource;
use App\Http\Resources\RequestFlightResource;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class GetFlightDetailResourceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_validate_fields(): void
    {
        $resource = new GetFlightDetailResource(
            flightId: 100,
            destination: 'Belo Horizonte - MG',
            status: FlightStatusEnum::APPROVED->value,
            departuneDate: '2025-01-20T23:00:00',
            returnDate: '2025-01-22T23:00:00',
            createdAt: '2025-01-07T23:00:00',
            updatedAt: '2025-01-18T09:10:26',
        );
        $this->assertIsArray($resource->toArray(new Request()));
        $this->assertEquals(
            expected: [
                'id' => 100,
                'status' => 'APPROVED',
                'destination' => 'Belo Horizonte - MG',
                'departune_date' => '2025-01-20T23:00:00',
                'return_date' => '2025-01-22T23:00:00',
                'created_at' => '2025-01-07T23:00:00',
                'updated_at' => '2025-01-18T09:10:26'
            ],
            actual: $resource->toArray(new Request())
        );
    }
}
