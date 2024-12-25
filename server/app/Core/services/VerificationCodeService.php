<?php

namespace App\Core\services;

use App\Core\Contracts\Mails\SendVerificationCodeMailInterface;
use App\Core\Contracts\Repositories\VerificationCodeRepositoryInterface;

class VerificationCodeService
{
    public function __construct(
        private VerificationCodeRepositoryInterface $verifiedCodeRepository,
        private SendVerificationCodeMailInterface $notifier
    ) {}

    public function handleVerification(int $customer_id, string $email): void
    {
        $verifiedCode = $this->verifiedCodeRepository->create($customer_id);
        $this->notifier->send($email, $verifiedCode->code);
    }
}
