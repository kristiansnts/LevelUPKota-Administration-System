<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $table = 'provinces';

    protected $primaryKey = 'provinsi_id';

    protected $fillable = [
        'provinsi_name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\City, $this>
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'provinsi_id', 'provinsi_id');
    }
}
