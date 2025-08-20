<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = [
        'name',
        'description',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (PaymentMethod $paymentMethod) {
            $paymentMethod->transactions()->delete();
        });
    }
}
