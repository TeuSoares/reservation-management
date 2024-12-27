<?php

namespace App\Core\Contracts\Repositories;

use App\Core\Domain\Entities\VerificationCode;

interface VerificationCodeRepositoryInterface extends RepositoryInterface
{
    public function findByNotExpiredCode(int $customerId): ?VerificationCode;
    public function findByNotVerifiedCode(int $code, int $customerId): ?VerificationCode;
    public function findByVerifiedCode(int $code, int $customerId): ?VerificationCode;
    public function create(int $customerId): VerificationCode;
    public function updateCodeToVerified(int $id): void;
    public function updateCodeToExpired(int $id): void;
    public function delete(int $id): void;
}
