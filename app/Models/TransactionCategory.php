<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    protected $table = 'transaction_category';

    protected $fillable = [
        'name',
        'description',
    ];
}
