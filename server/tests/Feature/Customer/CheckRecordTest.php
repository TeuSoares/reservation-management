<?php

use App\Core\Domain\Mails\VerificationCodeMail;
use App\Infrastructure\Persistence\Models\Customer;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->withHeaders([
        'Origin' => 'http://localhost',
    ]);
});

test('it should return customer id when customer found and send verification code', function () {
    $customer = Customer::factory()->create();

    Mail::fake();

    $response = $this->post(route('customers.check-record', ['cpf' => $customer->cpf]), [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['customer_id']
        ]);

    Mail::assertSent(VerificationCodeMail::class, $customer->email);
});

test('it should return 404 when customer not found', function () {
    $response = $this->post(route('customers.check-record', ['cpf' => '12345678901']), [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(404)
        ->assertJsonStructure([
            'errors' => [
                'not_found'
            ]
        ]);
});
