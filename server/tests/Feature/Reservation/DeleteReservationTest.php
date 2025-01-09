<?php

use App\Infrastructure\Persistence\Models\Customer;
use App\Infrastructure\Persistence\Models\Reservation;
use App\Infrastructure\Persistence\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->withHeaders([
        'Origin' => 'http://localhost'
    ]);
});

test('it should delete a reservation successfully', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $customer = Customer::newFactory()->create();
    $reservation = Reservation::newFactory()->create([
        'customer_id' => $customer->id,
        'number_people' => 2,
        'booking_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
        'canceled' => true
    ]);

    $response = $this->deleteJson(route('reservations.destroy', ['id' => $reservation->id]));

    $response->assertStatus(200);

    $this->assertDatabaseMissing('reservations', [
        'id' => $reservation->id
    ]);
});

test('it should not delete a reservation if it is not canceled', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $customer = Customer::newFactory()->create();
    $reservation = Reservation::newFactory()->create([
        'customer_id' => $customer->id,
        'number_people' => 2,
        'booking_date' => Carbon::now()->addDays(20)->format('Y-m-d'),
        'canceled' => false
    ]);

    $response = $this->deleteJson(route('reservations.destroy', ['id' => $reservation->id]));

    $response->assertStatus(422)
        ->assertJsonStructure([
            'errors' => [
                'reservation'
            ]
        ]);
});

test('it should not delete a reservation if booking date is bigger than today', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $customer = Customer::newFactory()->create();
    $reservation = Reservation::newFactory()->create([
        'customer_id' => $customer->id,
        'number_people' => 2,
        'booking_date' => Carbon::now()->addDays(20)->format('Y-m-d'),
        'canceled' => false
    ]);

    $response = $this->deleteJson(route('reservations.destroy', ['id' => $reservation->id]));

    $response->assertStatus(422)
        ->assertJsonStructure([
            'errors' => [
                'reservation'
            ]
        ]);
});

test('it should return 404 if the reservation does not exist', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $response = $this->deleteJson(route('reservations.destroy', ['id' => 0]));

    $response->assertStatus(404)
        ->assertJsonStructure([
            'errors' => [
                'not_found'
            ]
        ]);
});

test('it should return 401 if the user is not authenticated', function () {
    $reservation = Reservation::newFactory()->create();

    $response = $this->deleteJson(route('reservations.destroy', ['id' => $reservation->id]));

    $response->assertStatus(401)
        ->assertExactJson([
            'message' => 'Unauthenticated.'
        ]);
});
