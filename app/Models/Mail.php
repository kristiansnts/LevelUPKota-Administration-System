<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mail extends Model
{
    /** @use HasFactory<\Database\Factories\MailFactory> */
    use HasFactory;

    protected $fillable = [
        'mail_code',
        'mail_date',
        'mail_category_id',
        'sender_name',
        'receiver_name',
        'description',
        'type',
        'link',
        'status',
    ];

    public function mailCategory(): BelongsTo
    {
        return $this->belongsTo(MailCategory::class);
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
