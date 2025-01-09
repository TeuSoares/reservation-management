<?php

use App\Infrastructure\Persistence\Models\Reservation;
use App\Infrastructure\Persistence\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->reservation = Reservation::newFactory()->create();
    $this->formData = [
        'customer_id' => $this->reservation->customer_id,
        'booking_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
        'number_people' => 5,
        'canceled' => false
    ];
});

test('it should update a reservation successfully', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $reservation = $this->reservation;

    $response = $this->putJson(route('reservations.update', ['id' => $reservation->id]), $this->formData);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('reservations', [
        'id' => $reservation->id,
        'customer_id' => $reservation->customer_id,
        'booking_date' => $reservation->booking_date,
        'number_people' => $reservation->number_people
    ]);

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'customer_id' => $this->formData['customer_id'],
        'booking_date' => $this->formData['booking_date'],
        'number_people' => $this->formData['number_people']
    ]);
});

test('it should not update a reservation if the user is not authenticated', function () {
    $reservation = $this->reservation;

    $response = $this->putJson(route('reservations.update', ['id' => $reservation->id]), $this->formData);

    $response->assertStatus(401);

    $this->assertDataBaseHas('reservations', [
        'id' => $reservation->id,
        'customer_id' => $reservation->customer_id,
        'booking_date' => $reservation->booking_date,
        'number_people' => $reservation->number_people
    ]);
});
