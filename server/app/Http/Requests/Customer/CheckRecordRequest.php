<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CheckRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cpf' => 'required|size:11',
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.required' => 'O campo cpf é obrigatório',
            'cpf.size' => 'O campo cpf deve ter 11 dígitos',
        ];
    }
}
