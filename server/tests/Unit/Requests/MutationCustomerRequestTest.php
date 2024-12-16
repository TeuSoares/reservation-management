<?php

use App\Http\Requests\Customer\MutationCustomerRequest;
use App\Infrastructure\Persistence\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Mockery\MockInterface;

it('validates required fields for POST requests', function () {
    $data = [
        'name' => null,
        'email' => null,
        'cpf' => null,
        'phone' => null,
        'birth_date' => null,
    ];

    $request = new MutationCustomerRequest();

    $validator = Validator::make($data, $request->rules(), $request->messages());
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->all())->toEqual([
            'The name field is required.',
            'The email field is required.',
            'The cpf field is required.',
            'The phone field is required.',
            'The birth date field is required.',
        ]);
});

it('validates optional fields for PATCH requests', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'cpf' => '123456',
        'phone' => '12345',
        'birth_date' => 'invalid-date',
    ];

    $request = new MutationCustomerRequest();

    $validator = Validator::make($data, $request->rules(), $request->messages());
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->toArray())->toEqual([
            'email' => ['Email is invalid'],
            'cpf' => ['Cpf must be 11 digits'],
            'phone' => ['Phone must be 11 digits'],
            'birth_date' => ['Birth date is invalid', 'validation.after'],
        ]);
});
