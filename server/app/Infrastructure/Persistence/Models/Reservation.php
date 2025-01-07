<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'booking_date',
        'number_people',
        'payment_confirmed',
        'canceled',
    ];

    protected $attributes = [
        'payment_confirmed' => false,
        'canceled' => false,
    ];

    protected static function newFactory(): ReservationFactory
    {
        return ReservationFactory::new();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
