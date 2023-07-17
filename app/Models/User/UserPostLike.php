<?php

namespace App\Models\User;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPostLike extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'user_post_like';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'post_id'
    ];
}
