<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\VerifiedCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Persistence\Models\VerifiedCode>
 */
class VerifiedCodeFactory extends Factory
{
    protected $model = VerifiedCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->randomNumber(6),
            'customer_id' => fake()->numberBetween(1, 50),
            'verified' => fake()->boolean(),
            'expired' => fake()->boolean(),
        ];
    }
}
