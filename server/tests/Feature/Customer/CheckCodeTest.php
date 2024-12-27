<?php

use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\VerificationCode;

beforeEach(function () {
    $this->withHeaders([
        'Origin' => 'http://localhost',
    ]);
});

test('it should return 200 if verification code exists to customer', function () {
    $customer = Customer::newFactory()->create();
    $verificationCode = VerificationCode::newFactory()->create([
        'customer_id' => $customer->id,
        'code' => '123456',
        'verified' => false,
        'expired' => false
    ]);

    $response = $this->post(route('customers.check-code', ['code' => $verificationCode->code, 'id' => $customer->id]), [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(200);
});

test('it should failed if verification code not exists to customer', function () {
    $customer = Customer::newFactory()->create();

    $response = $this->postJson(route('customers.check-code', ['code' => 258796, 'id' => $customer->id]));

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['verification_code']);
});
