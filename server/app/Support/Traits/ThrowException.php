<?php

namespace App\Support\Traits;

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ThrowException
{
    protected function throwValidationException(array $data): void
    {
        throw ValidationException::withMessages($data);
    }

    protected function throwNotFoundException(string $message): void
    {
        throw new NotFoundHttpException($message);
    }
}
