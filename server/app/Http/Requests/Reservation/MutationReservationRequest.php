<?php

namespace App\Http\Requests\Reservation;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class MutationReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'customer_id' => 'required|integer|exists:customers,id',
            'booking_date' => 'sometimes|required|date' .
                '|after_or_equal:' . Carbon::now()->format('Y-m-d') .
                '|before_or_equal:' . Carbon::now()->addMonths(1)->format('Y-m-d'),
            'number_people' => 'sometimes|required|integer|min:1',
        ];

        if (request()->isMethod('POST')) {
            $rules['booking_date'] = str_replace('sometimes|', '', $rules['booking_date']);
            $rules['number_people'] = str_replace('sometimes|', '', $rules['number_people']);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer ID is required',
            'customer_id.integer' => 'Customer ID must be an integer',
            'customer_id.exists' => 'Customer ID does not exist',
            'booking_date.date' => 'Booking date must be a valid date',
            'booking_date.after' => 'Booking date must be today or later',
            'booking_date.before' => 'The reservation date must be next month at the latest',
            'number_people.integer' => 'Number of people must be an integer',
            'number_people.min' => 'Number of people must be at least 1',
        ];
    }
}
