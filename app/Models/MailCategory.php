<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailCategory extends Model
{
    protected $table = 'mail_category';

    protected $fillable = [
        'name',
        'description',
    ];
}
