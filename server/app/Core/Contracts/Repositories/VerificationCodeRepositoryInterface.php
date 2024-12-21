<?php

namespace App\Core\Contracts\Repositories;

use App\Core\Domain\Entities\VerificationCode;

interface VerificationCodeRepositoryInterface extends RepositoryInterface
{
    public function findByNotVerifiedCode(int $code, int $customerId): ?int;
    public function checkVerifiedCode(int $code, int $customerId): bool;
    public function create(int $customerId): VerificationCode;
    public function update(int $code, int $customerId): void;
}
