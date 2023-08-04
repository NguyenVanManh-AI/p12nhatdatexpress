<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectComment extends Model
{
    use HasFactory,
        SoftTrashed,
        AdminHistoryTrait;

    protected $table = 'project_comment';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $fillable = [
        'user_id',
        'project_id',
        'parent_id',
        'comment_content',
        'num_vote',
        'is_deleted',
        'is_confirmed',
        'is_show',
        'created_at',
        'updated_at',
        'is_new',
        'admin_id',
        'report'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    
    public function report_comment_project(){
        return $this->hasMany(ProjectReport::class,'project_comment_id','id')
            ->where(function ($query){
                $query->where(function ($q){
                    $q->where('report_result','=',1)->orWhere('report_result','=',null);
                })->where('report_position',2);
            })->with('report_group');
    }
    public function like_comment(){
        return $this->hasMany(ProjectLikeComment::class,'comment_id');
    }

    // public function likes() : HasMany
    // {
    //     return $this->hasMany(ProjectLikeComment::class, 'comment_id');
    // }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'project_like_comment',
            'comment_id',
            'user_id',
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ProjectReport::class);
    }

    /**
     * scopes
     */
    public function scopeShowed($query): Builder
    {
        return $query->where([
                'project_comment.is_show' => 1,
                'project_comment.is_deleted' => 0,
            ]);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            $comment->created_at = time();
        });

        static::updating(function ($comment) {
            $comment->updated_at = time();
        });

        static::deleting(function ($comment) {
            $comment->updated_at = time();
            $comment->save();
        });
    }
}
