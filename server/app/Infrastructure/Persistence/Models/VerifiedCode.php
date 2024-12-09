<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\VerifiedCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifiedCode extends Model
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

    protected static function newFactory(): VerifiedCodeFactory
    {
        return VerifiedCodeFactory::new();
    }
}
