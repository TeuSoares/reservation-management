<?php

namespace App\Core\UseCases\Reservation;

use App\Core\Contracts\Repositories\ReservationRepositoryInterface;
use App\Core\Domain\Entities\Reservation;
use App\Support\Traits\ThrowException;

class GetReservationByIdUseCase
{
    use ThrowException;

    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    public function execute(int $id): ?Reservation
    {
        $reservation = $this->reservationRepository->findById($id);

        if (!$reservation) $this->throwNotFoundException('Reservation not found');

        return $reservation;
    }
}
