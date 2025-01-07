<?php

use App\Core\Domain\Mails\ReservationCreated;
use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->withHeaders([
        'Origin' => 'http://localhost',
    ]);
});

test('it should create a reservation successfully and send email', function () {
    $customer = Customer::newFactory()->create();

    $verificationCode = VerificationCode::newFactory()->create([
        'customer_id' => $customer->id,
        'code' => '123456',
        'verified' => true,
        'expired' => false
    ]);

    $this->withHeaders([
        'X-Verification-Code' => $verificationCode->code,
    ]);

    $data = [
        'customer_id' => $customer->id,
        'booking_date' => '2025-01-03',
        'number_people' => 2
    ];

    Mail::fake();

    $response = $this->post(route('reservations.store'), $data, [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(201);

    Mail::assertSent(ReservationCreated::class);

    $this->assertDatabaseHas('reservations', $data);
    $this->assertDatabaseHas('verification_codes', [
        'id' => $verificationCode->id,
        'customer_id' => $customer->id,
        'code' => $verificationCode->code,
        'expired' => true
    ]);
});

test('it should not create a reservation if the verification code is expired', function () {
    $customer = Customer::newFactory()->create();

    $verificationCode = VerificationCode::newFactory()->create([
        'customer_id' => $customer->id,
        'code' => '123456',
        'verified' => true,
        'expired' => true
    ]);

    $this->withHeaders([
        'X-Verification-Code' => $verificationCode->code,
    ]);

    $data = [
        'customer_id' => $customer->id,
        'booking_date' => '2025-01-03',
        'number_people' => 2
    ];

    $response = $this->post(route('reservations.store'), $data, [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(403);

    $this->assertDatabaseMissing('reservations', $data);
});
