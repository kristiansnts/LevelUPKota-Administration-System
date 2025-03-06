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

    protected $table = 'finances';

    protected $fillable = [
        'period_id',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\TransactionCategory, $this>
     */
    public function transactionCategory(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\TransactionType, $this>
     */
    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\User, $this>
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\TransactionPeriod, $this>
     */
    public function transactionPeriod(): BelongsTo
    {
        return $this->belongsTo(TransactionPeriod::class);
    }
}
