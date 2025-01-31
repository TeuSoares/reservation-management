<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Core\Domain\Entities\Customer;
use App\Core\services\VerificationCodeService;
use App\Support\Traits\ThrowException;

class CheckRecordUseCase
{
    use ThrowException;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private VerificationCodeService $verifiedCodeService
    ) {}

    public function execute(string $cpf): Customer
    {
        $customer = $this->customerRepository->findByCpf($cpf);

        if (!$customer) $this->throwNotFoundException('Customer not found');

        $this->verifiedCodeService->handleVerification($customer->id, $customer->email);

        return $customer;
    }
}
