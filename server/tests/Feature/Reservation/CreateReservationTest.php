<?php

use App\Core\Domain\Mails\ReservationCreated;
use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->customer = Customer::newFactory()->create();

    $this->verificationCode = VerificationCode::newFactory()->create([
        'customer_id' => $this->customer->id,
        'code' => '123456',
        'verified' => true,
        'expired' => false
    ]);

    $this->withHeaders([
        'X-Verification-Code' => $this->verificationCode->code,
    ]);
});

test('it should create a reservation successfully and send email', function () {
    $data = [
        'customer_id' => $this->customer->id,
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
        'id' => $this->verificationCode->id,
        'customer_id' => $this->customer->id,
        'code' => $this->verificationCode->code,
        'expired' => true
    ]);
});

test('it should not create a reservation if the verification code is expired', function () {
    $this->verificationCode->expired = true;
    $this->verificationCode->save();

    $data = [
        'customer_id' => $this->customer->id,
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
    $data = [
        'customer_id' => $this->customer->id,
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
    $data = [
        'customer_id' => $this->customer->id,
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
    $data = [
        'customer_id' => $this->customer->id,
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
