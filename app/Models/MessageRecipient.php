<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MessageRecipient extends Pivot
{
    protected $table = 'message_recipients';
    protected $dates = ['read_at', 'deleted_at'];
    protected $fillable = ['message_id', 'recipient_id', 'read_at', 'deleted_at'];
}
