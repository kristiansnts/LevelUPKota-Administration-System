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
}
