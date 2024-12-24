<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Mails\SendVerificationCodeMailInterface;
use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Core\Contracts\Repositories\VerificationCodeRepositoryInterface;
use App\Support\Traits\ThrowException;
use Illuminate\Support\Facades\Auth;

class CreateCustomerUseCase
{
    use ThrowException;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private VerificationCodeRepositoryInterface $verifiedCodeRepository,
        private SendVerificationCodeMailInterface $notifier
    ) {}

    public function execute(array $data): string
    {
        $message = 'Customer created successfully';

        $customer = $this->customerRepository->create($data);

        if (Auth::user()) return $message;

        $verifiedCode = $this->verifiedCodeRepository->create($customer->id);

        $this->notifier->send($customer->email, $verifiedCode->code);

        return $message;
    }
}
