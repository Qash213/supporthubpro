<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplates extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'body', 'code', 'variables_used'
    ];
}
