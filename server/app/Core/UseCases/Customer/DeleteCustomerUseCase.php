<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Support\Traits\ThrowException;

class DeleteCustomerUseCase
{
    use ThrowException;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function execute(int $id): void
    {
        $this->customerRepository->delete($id);
    }
}
