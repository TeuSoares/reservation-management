<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Core\Domain\Entities\Customer;
use App\Support\Traits\ThrowException;

class UpdateCustomerUseCase
{
    use ThrowException;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function execute(array $data, int $id): Customer
    {
        return $this->customerRepository->update($data, $id);
    }
}
