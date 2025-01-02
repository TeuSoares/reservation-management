<?php

namespace App\Core\Contracts\Repositories;

use App\Core\Domain\Entities\Reservation;

interface ReservationRepositoryInterface extends RepositoryInterface
{
    public function getAll(object $params): object;
    public function findById(int $id): ?Reservation;
}
