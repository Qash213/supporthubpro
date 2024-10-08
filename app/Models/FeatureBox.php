<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureBox extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'title',
        'subtitle',
        'image',
        'storage_disk',
        'featureboxurl',
        'url_checkbox',
    ];
}
