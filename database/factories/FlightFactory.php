<?php

namespace Database\Factories;

use App\Enums\FlightStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departuneDate = $this->faker->dateTime();
        $countDays = rand(2, 7);

        return [
            'departune_date' => $departuneDate->format(\DateTime::ATOM),
            'return_date' => $departuneDate->add(new \DateInterval("P{$countDays}D"))->format(\DateTime::ATOM),
            'destination' => $this->faker->city()
        ];
    }

    /**
     * Indicate that the flight is requested.
     */
    public function requested(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => FlightStatusEnum::REQUESTED->value,
            ];
        });
    }

    /**
     * Indicate that the flight is approved.
     */
    public function approved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => FlightStatusEnum::APPROVED->value,
            ];
        });
    }

    /**
     * Indicate that the flight is canceled.
     */
    public function canceled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => FlightStatusEnum::CANCELED->value,
            ];
        });
    }
}
