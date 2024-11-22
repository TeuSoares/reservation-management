<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Repositories\CustomerRepositoryInterface;

class GetAllCustomers
{
    public function __construct(private CustomerRepositoryInterface $customerRepository) {}

    public function execute(): object
    {
        return $this->customerRepository->getAll();
    }
}
