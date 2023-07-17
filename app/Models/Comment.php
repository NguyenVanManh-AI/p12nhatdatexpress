<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory,
        SoftDeletes;
    use AdminHistoryTrait;


    protected $fillable = [
        'user_id', 'content', 'parent_id', 'commentable_id',
        'commentable_type', 'approved'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function commentable()
    // {
    //     return $this->morphTo();
    // }

    public function scopePublish($query)
    {
        return $query->where('comments.approved', true);
    }

    public function isReply()
    {
        return $this->parent_id ? true : false;
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')
                    ->publish()
					->withoutGlobalScopes();
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('parent_id', function(Builder $builder) {
            $builder->whereNull('parent_id');
        });
    }
}
