<?php

use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\User;
use App\Infrastructure\Persistence\Models\verificationCode;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->withHeaders([
        'Origin' => 'http://localhost',
    ]);
});

test('it should list all customers', function () {
    $user = User::newFactory()->create();

    Sanctum::actingAs($user);

    Customer::newFactory()->count(10)->create();

    $response = $this->getJson(route('customers.index'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'cpf',
                    'email',
                    'phone',
                    'birth_date'
                ]
            ],
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]
        ]);
});

test('it should list one customer by id', function () {
    $customer = Customer::newFactory()->create();

    $verificationCode = VerificationCode::newFactory()->create([
        'customer_id' => $customer->id,
        'code' => '123456',
        'verified' => true,
        'expired' => false
    ]);

    $this->withCookies([
        'verification_code' => $verificationCode->code,
    ]);

    $response = $this->get(route('customers.show', ['id' => $customer->id]), [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'cpf',
                'email',
                'phone',
                'birth_date'
            ]
        ]);
});

test('it should return 404 when customer not found', function () {
    $user = User::newFactory()->create();

    Sanctum::actingAs($user);

    $response = $this->get(route('customers.show', ['id' => 3]), [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(404)
        ->assertJsonStructure([
            'errors' => [
                'not_found'
            ]
        ]);
});
