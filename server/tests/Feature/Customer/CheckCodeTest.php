<?php

use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\VerificationCode;

beforeEach(function () {
    $this->customer = Customer::newFactory()->create();
});

test('it should return 200 if verification code exists to customer', function () {
    $verificationCode = VerificationCode::newFactory()->create([
        'customer_id' => $this->customer->id,
        'code' => '123456',
        'verified' => false,
        'expired' => false
    ]);

    $response = $this->post(route('customers.check-code', ['code' => $verificationCode->code, 'id' => $this->customer->id]), [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(200);
});

test('it should failed if verification code not exists to customer', function () {
    $response = $this->postJson(route('customers.check-code', ['code' => 258796, 'id' => $this->customer->id]));

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['verification_code']);
});
