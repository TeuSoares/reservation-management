<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf',
        'email',
        'phone',
        'birth_date',
    ];

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
