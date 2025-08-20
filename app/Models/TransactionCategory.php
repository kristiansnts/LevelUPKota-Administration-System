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

    /**
     * Get the transactions that belong to this category.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaction_category_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (TransactionCategory $category) {
            $category->transactions()->delete();
        });
    }
}
