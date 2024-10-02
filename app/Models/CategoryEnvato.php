<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryEnvato extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 
    protected $table = 'envatocategoryassign';
    protected $fillable = [
        'category_id',
        'envato_enable'
    ];
    
   
}
