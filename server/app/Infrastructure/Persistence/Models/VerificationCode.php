<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\VerificationCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_id',
        'verified',
        'expired',
    ];

    protected function casts(): array
    {
        return [
            'code' => 'integer',
        ];
    }

    protected static function newFactory(): VerificationCodeFactory
    {
        return VerificationCodeFactory::new();
    }
}
