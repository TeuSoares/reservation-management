<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Core\Domain\Entities\Customer as CustomerEntity;
use App\Infrastructure\Persistence\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(private Customer $customerModel) {}

    public function getAll(): object
    {
        $paginated = $this->customerModel->paginate(10);

        $paginated->getCollection()->transform(fn($customer) => $this->toEntity($customer));

        return (object) $paginated->toArray();
    }

    public function findById(int $id): ?CustomerEntity
    {
        $customer = $this->customerModel->find($id);

        return $customer ? $this->toEntity($customer) : null;
    }

    public function findByEmail(string $email): ?CustomerEntity
    {
        $customer = $this->customerModel->where('email', $email)->first();

        return $customer ? $this->toEntity($customer) : null;
    }

    public function findByCpf(string $cpf): ?CustomerEntity
    {
        $customer = $this->customerModel->where('cpf', $cpf)->first();

        return $customer ? $this->toEntity($customer) : null;
    }

    public function create(array $data): CustomerEntity
    {
        $customer = $this->customerModel->create($data);

        return $this->toEntity($customer);
    }

    public function toEntity(Model $customer): CustomerEntity
    {
        return new CustomerEntity(
            $customer->id,
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->cpf,
            $customer->birth_date
        );
    }
}
