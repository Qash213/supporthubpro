<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticketviolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'ticketviolation',
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
