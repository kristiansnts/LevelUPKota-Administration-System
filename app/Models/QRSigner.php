<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QRSigner extends Model
{
    use HasFactory;

    protected $table = 'qr_signer';

    protected $primaryKey = 'qr_signer_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'qr_signer_id',
        'signer_name',
        'signer_position',
        'phone_number',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->qr_signer_id)) {
                $model->qr_signer_id = (string) Str::ulid();
            }
        });
    }
}