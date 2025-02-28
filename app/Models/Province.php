<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $connection = 'indonesia_territory';

    protected $table = 'provinces';

    protected $primaryKey = 'prov_id';

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
