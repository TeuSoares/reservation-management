<?php

namespace App\Core\Contracts\Mails;

interface SendReservationCreatedInterface
{
    public function send(string $recipient, string $customer_name, int $number_people, string $booking_date): void;
}
