<?php

namespace App\Core\Domain\Entities;

use App\Core\Contracts\EntityInterface;
use Illuminate\Support\Carbon;

class VerificationCode implements EntityInterface
{
    public function __construct(
        public int $id,
        public int $code,
        public int $customer_id,
        public bool $verified,
        public bool $expired,
        public Carbon $created_at,
        public Carbon $updated_at
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'customer_id' => $this->customer_id,
            'verified' => $this->verified,
            'expired' => $this->expired,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
