<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CheckCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|numeric|digits:6',
            'id' => 'required|exists:customers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'The code is required',
            'code.digits' => 'The code must be 6 digits',
            'code.numeric' => 'The code must be numeric',
            'id.required' => 'The id is required',
            'id.exists' => 'The id not exists',
        ];
    }
}
