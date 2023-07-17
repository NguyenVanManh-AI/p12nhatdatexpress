<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\Models\AdminHistoryTrait;

class UserPostFollow extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'user_post_follow';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'follow_id'
    ];

    public function following()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function follower()
    {
        return $this->belongsTo(User::class,'follow_id');
    }
}
