<?php

namespace App\Core\UseCases\Customer;

use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Core\Domain\Entities\Customer;
use App\Support\Traits\ThrowException;

class GetCustomerById
{
    use ThrowException;

    public function __construct(private CustomerRepositoryInterface $customerRepository) {}

    public function execute(int $id): ?Customer
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) $this->throwNotFoundException('Customer not found');

        return $customer;
    }
}
