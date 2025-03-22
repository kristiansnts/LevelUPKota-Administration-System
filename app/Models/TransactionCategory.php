<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    protected $table = 'transaction_categories';

    protected $fillable = [
        'name',
        'description',
        'transaction_type',
    ];
}
