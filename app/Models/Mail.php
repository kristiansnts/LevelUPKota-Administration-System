<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
