<?php

namespace App\Core\Contracts\Mails;

interface SendVerificationCodeMailInterface
{
    public function send(string $recipient, int $code): void;
}
