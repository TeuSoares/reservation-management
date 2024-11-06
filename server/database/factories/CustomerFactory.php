<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'cpf' => fake()->unique()->cpf(false),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumberCleared(),
            'birth_date' => fake()->date(),
        ];
    }
}
