<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Core\Domain\Entities\Customer;

class GetCustomerById
{
    public function __construct(private CustomerRepositoryInterface $customerRepository) {}

    public function execute(int $id): ?Customer
    {
        return $this->customerRepository->findById($id);
    }
}
