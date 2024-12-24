<?php

use App\Core\Domain\Mails\VerificationCodeMail;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

test('it should create a new customer successfully and send email with verification code', function () {
    $customer = [
        'name' => 'John Doe',
        'cpf' => '12345678901',
        'email' => 'test@example.com',
        'phone' => '11999999999',
        'birth_date' => '2000-01-01',
    ];

    Mail::fake();

    $response = $this->postJson(route('customers.store'), $customer);
    $response->assertStatus(201);

    $this->assertDatabaseHas('customers', $customer);

    Mail::assertSent(VerificationCodeMail::class, $customer['email']);
});

test('if admin is logged in, it should create a new customer successfully without sending email with verification code', function () {
    $customer = [
        'name' => 'John Doe',
        'cpf' => '12345678901',
        'email' => 'test@example.com',
        'phone' => '11999999999',
        'birth_date' => '2000-01-01',
    ];

    $user = User::newFactory()->create();
    Sanctum::actingAs($user);

    Mail::fake();

    $response = $this->postJson(route('customers.store'), $customer);
    $response->assertStatus(201);

    $this->assertDatabaseHas('customers', $customer);

    Mail::assertNotSent(VerificationCodeMail::class);
});
