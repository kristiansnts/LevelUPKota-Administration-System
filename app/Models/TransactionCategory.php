<?php

namespace App\Models;

use App\Enums\FinanceTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * Get the formatted name with transaction type
     */
    protected function nameWithType(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $typeName = $this->transaction_type === FinanceTypeEnum::INCOME->value 
                    ? 'Pemasukan' 
                    : 'Pengeluaran';
                return "{$this->name} - {$typeName}";
            }
        );
    }

    /**
     * Get the full formatted display with description
     */
    protected function fullDisplay(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $display = $this->name_with_type;
                if ($this->description) {
                    $display .= "\n" . $this->description;
                }
                return $display;
            }
        );
    }

    protected static function booted(): void
    {
        static::deleting(function (TransactionCategory $category) {
            $category->transactions()->delete();
        });
    }
}
