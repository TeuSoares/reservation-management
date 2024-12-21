<?php

namespace App\Infrastructure\Persistence\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Core\Contracts\Repositories\VerificationCodeRepositoryInterface;
use App\Core\Domain\Entities\VerificationCode;
use App\Infrastructure\Persistence\Models\VerificationCode as VerificationCodeModel;
use Carbon\Carbon;

class VerificationCodeRepository implements VerificationCodeRepositoryInterface
{
    public function __construct(private VerificationCodeModel $model) {}

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

    public function create(int $customerId): VerificationCode
    {
        $row = $this->model->create([
            'code' => rand(100000, 999999),
            'customer_id' => $customerId,
            'verified' => false,
            'expired' => false,
        ]);

        return $this->toEntity($row);
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

    public function toEntity(Model $model): VerificationCode
    {
        return new VerificationCode(
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
