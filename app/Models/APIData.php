<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class APIData extends Model
{
    use HasFactory;

    protected $table = 'envatoapitoken';

    protected $fillable = [
        'envatoapitoken',
        'envatoapitokensecond',
        'envatoapitokenthird'
    ];
}
