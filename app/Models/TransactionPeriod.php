<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPeriod extends Model
{
    protected $table = 'transaction_period';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];
}
