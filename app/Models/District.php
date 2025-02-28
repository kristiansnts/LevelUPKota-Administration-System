<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{

    protected $connection = 'indonesia_territory';

    protected $table = 'district';

    protected $primaryKey = 'dis_id';

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
