<?php

namespace App\Infrastructure\Persistence\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Core\Contracts\Repositories\VerifiedCodeRepositoryInterface;
use App\Core\Domain\Entities\VerifiedCode as VerifiedCodeEntity;
use App\Infrastructure\Persistence\Models\VerifiedCode;
use Carbon\Carbon;

class VerifiedCodeRepository implements VerifiedCodeRepositoryInterface
{
    public function __construct(private VerifiedCode $model) {}

    public function findByNotVerifiedCode(int $code, int $customerId): ?int
    {
        $notVerifiedCode = $this->model->where('code', $code)
            ->whereDate('created_at', Carbon::today())
            ->where('customer_id', $customerId)
            ->where('verified', false)
            ->where('expired', false)
            ->first();

        return $notVerifiedCode ? $notVerifiedCode->code : null;
    }

    public function checkVerifiedCode(int $code, int $customerId): bool
    {
        $verifiedCode = $this->model->where('code', $code)
            ->whereDate('created_at', Carbon::today())
            ->where('customer_id', $customerId)
            ->where('verified', true)
            ->where('expired', false)
            ->first();

        return $verifiedCode ? true : false;
    }

    public function create(int $customerId): void
    {
        $this->model->create([
            'code' => rand(100000, 999999),
            'customer_id' => $customerId,
            'verified' => false,
            'expired' => false,
        ]);
    }

    public function update(int $code, int $customerId): void
    {
        $this->model->where('code', $code)
            ->whereDate('created_at', Carbon::today())
            ->where('customer_id', $customerId)
            ->where('verified', false)
            ->where('expired', false)
            ->update([
                'verified' => true,
            ]);
    }

    public function toEntity(Model $model): VerifiedCodeEntity
    {
        return new VerifiedCodeEntity(
            $model->id,
            $model->code,
            $model->customer_id,
            $model->verified,
            $model->expired,
            $model->created_at,
            $model->updated_at
        );
    }
}
