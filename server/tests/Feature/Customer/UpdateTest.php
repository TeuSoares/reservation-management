<?php

use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\User;
use App\Infrastructure\Persistence\Models\VerificationCode;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->customer = Customer::newFactory()->create();
});

test('it should update a customer successfully', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $response = $this->putJson(route('customers.update', ['id' => $this->customer->id]), [
        'name' => 'John Doe',
        'phone' => '12345678978',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('customers', [
        'id' => $this->customer->id,
        'name' => $this->customer->name,
        'phone' => $this->customer->phone,
    ]);

    $this->assertDatabaseHas('customers', [
        'id' => $this->customer->id,
        'name' => 'John Doe',
        'phone' => '12345678978',
    ]);
});

test('it should update a customer successfully if customer has permission', function () {
    $verificationCode = VerificationCode::newFactory()->create([
        'customer_id' => $this->customer->id,
        'code' => '123456',
        'verified' => true,
        'expired' => false
    ]);

    $this->withHeaders([
        'X-Verification-Code' => $verificationCode->code,
    ]);

    $response = $this->putJson(route('customers.update', ['id' => $this->customer->id]), [
        'name' => 'John Doe',
        'phone' => '12345678978',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('customers', [
        'id' => $this->customer->id,
        'name' => $this->customer->name,
        'phone' => $this->customer->phone,
    ]);

    $this->assertDatabaseHas('customers', [
        'id' => $this->customer->id,
        'name' => 'John Doe',
        'phone' => '12345678978',
    ]);
});

test('it should not update a customer if not have permission', function () {
    $customer = $this->customer;

    $response = $this->putJson(route('customers.update', ['id' => $customer->id]));

    $response->assertStatus(401);
});

test('it should not update a customer if not found', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $response = $this->putJson(route('customers.update', ['id' => 0]));

    $response->assertStatus(404)
        ->assertJsonStructure([
            'errors' => [
                'not_found'
            ]
        ]);
});
