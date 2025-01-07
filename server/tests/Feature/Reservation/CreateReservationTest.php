<?php

use App\Core\Domain\Mails\ReservationCreated;
use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\VerificationCode;
use Carbon\Carbon;
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
        'booking_date' => Carbon::now()->format('Y-m-d'),
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

test('it should not create a reservation if the booking date is in the past', function () {
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
        'booking_date' => '2025-01-06',
        'number_people' => 2
    ];

    Mail::fake();

    $response = $this->post(route('reservations.store'), $data, [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(422);

    Mail::assertNothingSent(ReservationCreated::class);

    $this->assertDatabaseMissing('reservations', $data);
});

test('it should not create a reservation if the number of people is less than 1', function () {
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
        'booking_date' => Carbon::now()->format('Y-m-d'),
        'number_people' => 0
    ];

    Mail::fake();

    $response = $this->post(route('reservations.store'), $data, [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['number_people']);

    Mail::assertNothingSent(ReservationCreated::class);

    $this->assertDatabaseMissing('reservations', $data);
});

test('it should not create a reservation if the booking date is after 1 month', function () {
    $customer = Customer::newFactory()->create();

    $verificationCode = VerificationCode::newFactory()->create([
        'customer_id' => $customer->id,
        'code' => '123456',
        'verified' => true,
        'expired' => false,
    ]);

    $this->withHeaders([
        'X-Verification-Code' => $verificationCode->code,
    ]);

    $data = [
        'customer_id' => $customer->id,
        'booking_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
        'number_people' => 5,
    ];

    Mail::fake();

    $response = $this->post(route('reservations.store'), $data, [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['booking_date']);

    Mail::assertNothingSent(ReservationCreated::class);

    $this->assertDatabaseMissing('reservations', $data);
});
