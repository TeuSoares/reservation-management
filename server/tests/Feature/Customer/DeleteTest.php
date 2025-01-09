<?php

use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\User;
use App\Infrastructure\Persistence\Models\VerificationCode;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->customer = Customer::newFactory()->create();
});

test('it should delete a customer successfully', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $customer = $this->customer;

    VerificationCode::newFactory()->create(['customer_id' => $customer->id]);

    $response = $this->deleteJson(route('customers.destroy', ['id' => $customer->id]));

    $response->assertStatus(200);

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    $this->assertDatabaseMissing('verification_codes', ['customer_id' => $customer->id]);
});

test('it should not delete a customer if not authenticated', function () {
    $customer = $this->customer;

    $response = $this->deleteJson(route('customers.destroy', ['id' => $customer->id]));

    $response->assertStatus(401);

    $this->assertDatabaseHas('customers', ['id' => $customer->id]);
});

test('it should not delete a customer if not found', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $response = $this->deleteJson(route('customers.destroy', ['id' => 0]));

    $response->assertStatus(404)
        ->assertJsonStructure([
            'errors' => [
                'not_found'
            ]
        ]);
});
