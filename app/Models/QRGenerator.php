<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mail;
use Illuminate\Support\Str;

class QRGenerator extends Model
{
    use HasFactory;

    protected $table = 'qr_generator';

    protected $primaryKey = 'qr_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'qr_id',
        'document_id',
    ];

    public function document()
    {
        return $this->belongsTo(Mail::class, 'document_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->qr_id)) {
                $model->qr_id = (string) Str::ulid();
            }
        });
    }
}