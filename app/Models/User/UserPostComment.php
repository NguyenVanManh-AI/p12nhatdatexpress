<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserPostComment extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected $table = 'user_post_comment';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_post_id',
        'parent_id',
        'comment_content',
        'num_like',
        'is_new',
        'is_show',
        'is_block',
        'is_deleted',
        'created_at',
        'updated_at',
        'report'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }

    // hide or delete
    public function report_post_comment(){
        return $this->hasMany(UserPostReport::class,'user_post_comment_id','id')
            ->where(function ($query){
                $query->where(function ($q){
                    $q->where('report_result','=',1)->orWhere('report_result','=',null);
                })->where('report_position',2);
            })->with('report_group');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(UserPostReport::class,'user_post_comment_id', 'id');
    }

    public function child(){
        return $this->hasMany(UserPostComment::class,'parent_id');
    }
    public function like(){
        return $this->hasMany(UserPostLikeComment::class,'comment_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
