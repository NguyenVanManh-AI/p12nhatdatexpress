<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPostLikeComment extends Model
{
    use HasFactory;
    protected $table = 'user_post_like_comment';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'comment_id',
        'post_id'
    ];
}
