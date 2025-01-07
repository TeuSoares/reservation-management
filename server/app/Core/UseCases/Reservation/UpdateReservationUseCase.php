<?php

namespace App\Core\UseCases\Reservation;

use App\Core\Contracts\Repositories\ReservationRepositoryInterface;
use App\Support\Traits\ThrowException;

class UpdateReservationUseCase
{
    use ThrowException;

    public function __construct(
        private ReservationRepositoryInterface $repository
    ) {}

    public function execute(int $id, array $data): void
    {
        $this->repository->update($id, $data);
    }
}
