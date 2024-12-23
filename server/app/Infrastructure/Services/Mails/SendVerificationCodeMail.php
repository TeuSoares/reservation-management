<?php

namespace App\Infrastructure\Services\Mails;

use App\Core\Contracts\Mails\SendVerificationCodeMailInterface;
use App\Core\Domain\Mails\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;

class SendVerificationCodeMail implements SendVerificationCodeMailInterface
{
    public function send(string $recipient, int $code): void
    {
        Mail::to($recipient)->send(new VerificationCodeMail($code));
    }
}
