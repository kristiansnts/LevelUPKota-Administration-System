<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Get the users associated with this report.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\User>
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'report_user')
                    ->withPivot(['city_id', 'district_id']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ReportUser, $this>
     */
    public function reportUsers(): HasMany
    {
        return $this->hasMany(ReportUser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Transaction, $this>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Report $report) {
            // Set report_id to null for all transactions instead of deleting them
            // This is now handled by the database foreign key constraint (onDelete SET NULL)
            // But we keep this for explicit clarity
            $report->transactions()->update(['report_id' => null]);
        });
    }
}
