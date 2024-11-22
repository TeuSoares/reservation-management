<?php

namespace App\Core\Domain\Entities;

use App\Core\Contracts\EntityInterface;

class Customer implements EntityInterface
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $phone,
        public string $cpf,
        public string $birth_date,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'cpf' => $this->cpf,
            'birth_date' => $this->birth_date
        ];
    }
}
