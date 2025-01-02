<?php

namespace App\Core\UseCases\Reservation;

use App\Core\Contracts\Repositories\ReservationRepositoryInterface;
use App\Support\Traits\ThrowException;

class GetAllReservationUseCase
{
    use ThrowException;

    public function __construct(private ReservationRepositoryInterface $repository) {}

    public function execute(array $data)
    {
        return $this->repository->getAll((object) $data);
    }
}
