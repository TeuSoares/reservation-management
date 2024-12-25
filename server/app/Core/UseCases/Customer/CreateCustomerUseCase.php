<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Core\services\VerificationCodeService;
use App\Support\Traits\ThrowException;
use Illuminate\Support\Facades\Auth;

class CreateCustomerUseCase
{
    use ThrowException;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private VerificationCodeService $verifiedCodeService
    ) {}

    public function execute(array $data): int
    {
        $customer = $this->customerRepository->create($data);

        if (Auth::user()) return $customer->id;

        $this->verifiedCodeService->handleVerification($customer->id, $customer->email);

        return $customer->id;
    }
}
