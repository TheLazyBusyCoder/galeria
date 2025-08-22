<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    protected $fillable = ['message_id', 'type', 'url', 'thumbnail_url', 'size', 'metadata'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}