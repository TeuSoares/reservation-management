<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Persistence\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

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
