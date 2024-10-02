<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class livechatReviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'cust_id',
        'starRating',
        'problemRectified',
        'feedBackData',
    ];
}
