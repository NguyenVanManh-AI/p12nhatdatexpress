<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserPost extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;
    
    protected $table = 'user_post';
    public $timestamps = false;

    protected $fillable = [
        'post_code',
        'user_id',
        'post_content',
        'post_image',
        'num_view',
        'num_like',
        'is_show',
        'is_confirm',
        'is_deleted',
        'is_block',
        'meta_title',
        'meta_key',
        'meta_desc',
        'created_at',
        'created_by',
        'report'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }

    // hide or delete 
    public function report_post(){
        return $this->hasMany(UserPostReport::class,'user_post_id','id')
            ->where(function ($query){
                $query->where(function ($q){
                    $q->where('report_result','=',1)->orWhere('report_result','=',null);
                })->where('report_position',1);
            })->with('report_group');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(UserPostReport::class);
    }

    public function comment(){
        return $this->hasMany(UserPostComment::class,'user_post_id')->whereHas('user');
    }
    public function like(){
        return $this->hasMany(UserPostLike::class,'post_id');
    }

    /**
	 * Scope a query to only include
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeShowed($query): Builder
	{
		return $query->where([
			$this->getTable() . '.is_show' => 1,
		]);
	}
}
