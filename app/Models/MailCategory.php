<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailCategory extends Model
{
    protected $table = 'mail_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    public function mails()
    {
        return $this->hasMany(Mail::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (MailCategory $category) {
            $category->mails()->delete();
        });
    }
}
