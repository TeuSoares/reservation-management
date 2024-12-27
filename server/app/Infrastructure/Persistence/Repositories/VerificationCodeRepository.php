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

    public function findByNotExpiredCode(int $customerId): ?VerificationCode
    {
        $verificationCode = $this->model->where('customer_id', $customerId)
            ->where(function ($query) {
                $query->where('verified', true)
                    ->orWhere('verified', false);
            })
            ->where('expired', false)
            ->first();

        return $verificationCode ? $this->toEntity($verificationCode) : null;
    }

    public function findByNotVerifiedCode(int $code, int $customerId): ?VerificationCode
    {
        $notVerifiedCode = $this->model->where('code', $code)
            ->whereDate('created_at', Carbon::today())
            ->where('customer_id', $customerId)
            ->where('verified', false)
            ->where('expired', false)
            ->first();

        return $notVerifiedCode ? $this->toEntity($notVerifiedCode) : null;
    }

    public function findByVerifiedCode(int $code, int $customerId): ?VerificationCode
    {
        $verifiedCode = $this->model->where('code', $code)
            ->whereDate('created_at', Carbon::today())
            ->where('customer_id', $customerId)
            ->where('verified', true)
            ->where('expired', false)
            ->first();

        return $verifiedCode ? $this->toEntity($verifiedCode) : null;
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

    public function updateCodeToVerified(int $id): void
    {
        $this->model->find($id)->update([
            'verified' => true
        ]);
    }

    public function updateCodeToExpired(int $id): void
    {
        $this->model->find($id)->update([
            'expired' => true
        ]);
    }

    public function delete(int $id): void
    {
        $this->model->find($id)->delete();
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
