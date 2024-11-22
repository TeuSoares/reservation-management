<?php

namespace App\Core\Contracts\Repositories;

use App\Core\Domain\Entities\Customer;

interface CustomerRepositoryInterface extends RepositoryInterface
{
    public function getAll(): object;
    public function findById(int $id): ?Customer;
}