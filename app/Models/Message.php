<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'type', 'content'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'message_recipients', 'message_id', 'recipient_id')
                    ->withPivot('read_at', 'deleted_at')
                    ->withTimestamps();
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }
}
