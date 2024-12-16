<?php

namespace App\Http\Requests\Customer;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class MutationCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $eighteenYearsAgo = Carbon::now()->subYears(18)->format('Y-m-d');
        $oneHundredYearsAgo = Carbon::now()->subYears(100)->format('Y-m-d');

        $rules = [
            'name' => 'sometimes|required|max:255',
            'email' => 'sometimes|required|email|unique:customers,email,' . $this->id,
            'cpf' => 'sometimes|required|size:11|unique:customers,cpf,' . $this->id,
            'phone' => 'sometimes|required|digits:11',
            'birth_date' => [
                'sometimes',
                'required',
                'date',
                'before:' . $eighteenYearsAgo,
                'after:' . $oneHundredYearsAgo,
            ],
        ];

        if (request()->isMethod('POST')) {
            foreach ($rules as &$rule) {
                $rule = str_replace('sometimes|', '', $rule);
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must not exceed :max characters.',
            'email.email' => 'Email is invalid',
            'email.unique' => 'Email is already in use',
            'cpf.size' => 'Cpf must be 11 digits',
            'cpf.unique' => 'Cpf is already in use',
            'phone.digits' => 'Phone must be 11 digits',
            'birth_date.date' => 'Birth date is invalid',
        ];
    }
}
