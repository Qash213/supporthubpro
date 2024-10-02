<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imap_setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'imap_host',
        'imap_port',
        'imap_protocol',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'category_id',
        'status'
    ];
}
