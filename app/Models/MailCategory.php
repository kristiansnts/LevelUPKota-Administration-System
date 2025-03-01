<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];
}
