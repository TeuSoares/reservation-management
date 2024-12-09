<?php

namespace App\Core\Contracts\Repositories;

interface VerifiedCodeRepositoryInterface extends RepositoryInterface
{
    public function findByNotVerifiedCode(int $code, int $customerId): ?int;
    public function checkVerifiedCode(int $code, int $customerId): bool;
    public function create(int $customerId): void;
    public function update(int $code, int $customerId): void;
}
