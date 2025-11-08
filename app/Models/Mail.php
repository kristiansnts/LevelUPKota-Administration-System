<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Mail extends Model
{
    /** @use HasFactory<\Database\Factories\MailFactory> */
    use HasFactory;

    protected $fillable = [
        'mail_unique',
        'mail_code',
        'mail_date',
        'mail_category_id',
        'sender_name',
        'receiver_name',
        'description',
        'type',
        'file_name',
        'file_id',
        'file_url',
    ];

    protected $casts = [
        'receiver_name' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\MailCategory, $this>
     */
    public function mailCategory(): BelongsTo
    {
        return $this->belongsTo(MailCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\User, $this>
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function mailUsers(): HasMany
    {
        return $this->hasMany(MailUser::class);
    }

    public function qrGenerators(): HasMany
    {
        return $this->hasMany(QRGenerator::class, 'document_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Mail $mail) {
            if (empty($mail->mail_unique)) {
                $mail->mail_unique = (string) Str::ulid();
            }
        });

        static::deleting(function (Mail $mail) {
            $mail->mailUsers()->delete();
            $mail->qrGenerators()->delete();
        });
    }
}
