<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoModel extends Model
{
    use HasFactory;

    public $table = 'photos';

    protected $fillable = [
        'user_id',
        'image_path',
        'caption',
        'likes_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class , 'photo_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class , 'photo_id');
    }
}