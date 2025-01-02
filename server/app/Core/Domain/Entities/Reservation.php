<?php

namespace App\Core\Domain\Entities;

use App\Core\Contracts\EntityInterface;

class Reservation implements EntityInterface
{
    public function __construct(
        public int $id,
        public int $customer_id,
        public string $booking_date,
        public int $number_people,
        public bool $payment_confirmed,
        public bool $canceled
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'booking_date' => $this->booking_date,
            'number_people' => $this->number_people,
            'payment_confirmed' => $this->payment_confirmed,
            'canceled' => $this->canceled
        ];
    }
}
