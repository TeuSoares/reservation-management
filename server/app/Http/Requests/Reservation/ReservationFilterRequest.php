<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:reservations,id',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'booking_date' => 'nullable|date',
            'canceled' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'The selected reservation does not exist.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'booking_date.date' => 'The booking date must be a valid date.',
            'canceled.boolean' => 'The canceled field must be true or false.',
        ];
    }
}
