<?php

namespace App\Core\Domain\Entities;

use App\Core\Contracts\EntityInterface;
use Carbon\Carbon;

class Reservation implements EntityInterface
{
    public function __construct(
        public int $id,
        public int $customer_id,
        public Carbon $booking_date,
        public int $number_people,
        public bool $canceled,
        public ?Customer $customer = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'booking_date' => $this->booking_date,
            'number_people' => $this->number_people,
            'canceled' => $this->canceled,
            'customer' => $this->customer
        ];
    }
}
