<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Finance extends Model
{
    /** @use HasFactory<\Database\Factories\FinanceFactory> */
    use HasFactory;

    protected $fillable = [
        'period',
        'transaction_date',
        'transaction_category_id',
        'description',
        'transaction_type_id',
        'amount_in',
        'amount_out',
        'invoice_code',
        'type',
        'transaction_proof_link',
    ];

    public function transactionCategory(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
