<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $table = 'follows';

    protected $fillable = [
        'follower_id',
        'followed_id',
        'status',
    ];

    // Who follows
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    // Who is being followed
    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
