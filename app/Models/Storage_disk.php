<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage_disk extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'logo',
        'storage_disk',
        'provider',
        'credentials_data',
        'status',
    ];
}
