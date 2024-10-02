<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAuthSetting extends Model
{
    use HasFactory;

    protected $fillable =[
        'google_client_id',
        'google_secret_id',
        'google_status',
        'microsoft_app_id',
        'microsoft_secret_id',
        'microsoft_status',
    ];
}
