<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'title',
        'period',
        'is_done',
        'transaction_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Transaction, $this>
     */
    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
