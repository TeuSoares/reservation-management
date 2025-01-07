<?php

namespace App\Infrastructure\Services\Mails;

use App\Core\Contracts\Mails\SendReservationCreatedInterface;
use App\Core\Domain\Mails\ReservationCreated;
use Illuminate\Support\Facades\Mail;

class SendReservationCreated implements SendReservationCreatedInterface
{
    public function send(string $recipient, string $customer_name, int $number_people, string $booking_date): void
    {
        Mail::to($recipient)->send(new ReservationCreated($customer_name, $number_people, $booking_date));
    }
}
