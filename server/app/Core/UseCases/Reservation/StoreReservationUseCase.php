<?php

namespace App\Core\UseCases\Reservation;

use App\Core\Contracts\Mails\SendReservationCreatedInterface;
use App\Core\Contracts\Repositories\ReservationRepositoryInterface;
use App\Core\Contracts\Repositories\VerificationCodeRepositoryInterface;
use App\Support\Traits\ThrowException;

class StoreReservationUseCase
{
    use ThrowException;

    public function __construct(
        private ReservationRepositoryInterface $repository,
        private VerificationCodeRepositoryInterface $verificationCodeRepository,
        private SendReservationCreatedInterface $notifier
    ) {}

    public function execute(array $data): void
    {
        $verificationCode = (object) $data['verification_code'];

        $reservation = $this->repository->create($data);

        $customer = $reservation->customer;

        $this->notifier->send($customer->email, $customer->name, $reservation->number_people, $reservation->booking_date->format('d/m/Y H:i'));

        $this->verificationCodeRepository->updateCodeToExpired($verificationCode->id);
    }
}
