<?php

namespace App\Core\UseCases\Reservation;

use App\Core\Contracts\Repositories\ReservationRepositoryInterface;
use App\Support\Traits\ThrowException;

class DeleteReservationUseCase
{
    use ThrowException;

    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    public function execute(int $id): void
    {
        $reservation = $this->reservationRepository->findById($id);

        if (!$reservation) $this->throwNotFoundException('Reservation not found');

        if ($reservation->booking_date->format('Y-m-d') >= now()->format('Y-m-d') && !$reservation->canceled) {
            $this->throwValidationException(['reservation' => 'You cannot delete a reservation that has not been canceled yet']);
        }

        $this->reservationRepository->delete($reservation->id);
    }
}
