<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    protected $primaryKey = 'kecamatan_id';

    protected $fillable = [
        'kabupaten_id',
        'kecamatan_name'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kabupaten_id', 'kabupaten_id');
    }
}
