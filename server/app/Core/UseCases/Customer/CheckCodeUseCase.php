<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Repositories\VerificationCodeRepositoryInterface;
use App\Support\Traits\ThrowException;

class CheckCodeUseCase
{
    use ThrowException;

    public function __construct(
        private VerificationCodeRepositoryInterface $verificationCodeRepository
    ) {}

    public function execute(int $code, int $customerId): int
    {
        $verificationCode = $this->verificationCodeRepository->findByNotVerifiedCode($code, $customerId);

        if (!$verificationCode) $this->throwValidationException(['verification_code' => 'Invalid verification code']);

        $this->verificationCodeRepository->updateCodeToVerified($verificationCode->id);

        return $verificationCode->code;
    }
}
