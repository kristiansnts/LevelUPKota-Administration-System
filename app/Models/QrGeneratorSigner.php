<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QRGenerator;
use App\Models\QRSigner;
use Illuminate\Support\Str;

class QrGeneratorSigner extends Model
{
    protected $table = 'qr_generator_qr_signer';

    protected $primaryKey = 'qr_generator_qr_signer_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'qr_generator_qr_signer_id',
        'qr_generator_id',
        'qr_signer_id',
        'status',
        'total_sign',
        'rejection_notes',
    ];

    public function qrGenerator()
    {
        return $this->belongsTo(QRGenerator::class, 'qr_generator_id', 'qr_id');
    }

    public function qrSigner()
    {
        return $this->belongsTo(QRSigner::class, 'qr_signer_id', 'qr_signer_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->qr_generator_qr_signer_id)) {
                $model->qr_generator_qr_signer_id = (string) Str::ulid();
            }
        });
    }
}