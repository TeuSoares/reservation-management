<?php

use App\Infrastructure\Persistence\Models\Reservation;
use App\Infrastructure\Persistence\Models\User;
use Laravel\Sanctum\Sanctum;

test('it should list all reservations', function () {
    Sanctum::actingAs(User::newFactory()->create());

    Reservation::newFactory()->count(10)->create();

    $response = $this->getJson(route('reservations.index', [
        'customer_id' => 1
    ]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'customer_id',
                    'booking_date',
                    'number_people',
                    'canceled'
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

test('it should fail to list reservations if user is not authenticated', function () {
    $response = $this->getJson(route('reservations.index'));

    $response->assertStatus(401)
        ->assertExactJson([
            'message' => 'Unauthenticated.'
        ]);
});

test('it should list one reservation by id', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $reservation = Reservation::newFactory()->create();

    $response = $this->getJson(route('reservations.show', ['id' => $reservation->id]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'customer_id',
                'booking_date',
                'number_people',
                'canceled'
            ]
        ]);
});

test('it should return 404 when reservation not found', function () {
    Sanctum::actingAs(User::newFactory()->create());

    $response = $this->getJson(route('reservations.show', ['id' => 3]));

    $response->assertStatus(404)
        ->assertJsonStructure([
            'errors' => [
                'not_found'
            ]
        ]);
});
