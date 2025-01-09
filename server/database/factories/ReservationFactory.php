<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Persistence\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::newFactory(),
            'booking_date' => fake()->dateTime(),
            'number_people' => fake()->numberBetween(1, 10),
            'canceled' => fake()->boolean(),
        ];
    }
}
