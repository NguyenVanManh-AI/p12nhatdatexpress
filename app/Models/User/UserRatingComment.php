<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRatingComment extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table ='user_rating_comment';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'comment_content',
        'persolnal_id',
        'parent_id',
        'created_at'
    ];


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function persolnal(){
        return $this->belongsTo(User::class,'persolnal_id');
    }
    public function child(){
        return $this->hasMany(UserRatingComment::class,'parent_id');
    }
    public function like(){
        return $this->hasMany(UserRatingLike::class,'comment_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            $comment->created_at = time();
        });
    }
}
