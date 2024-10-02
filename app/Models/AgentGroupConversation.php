<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentGroupConversation extends Model
{
    use HasFactory;

    protected $fillable  = [
        'unique_id',
        'created_userid',
        'message',
        'message_type',
        'sender_user_id',
        'receiver_user_id',
        'delete_status',
        'mark_as_unread',
        'message_status'
    ];
}
