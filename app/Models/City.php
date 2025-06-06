<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $primaryKey = 'kabupaten_id';

    protected $fillable = [
        'provinsi_id',
        'kabupaten_name'
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'provinsi_id', 'provinsi_id');
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'kabupaten_id', 'kabupaten_id');
    }
}
