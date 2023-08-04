<?php

namespace App\Models;

use App\Enums\NewLikeTypeEnum;
use App\Traits\Filterable;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\File;

class News extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;
    use Filterable;

    protected  $table = 'news';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'news_title',
        'news_description',
        'news_content',
        'news_url',
        'video_url',
        'image_url',
        'audio_url',
        'tag_list',
        'num_view',
        'num_like',
        'num_dislike',
        'is_express',
        'express_start',
        'express_end',
        'is_highlight',
        'highlight_start',
        'highlight_end',
        'renew_at',
        'highlight_from',
        'highlight_to',
        'meta_title',
        'meta_key',
        'meta_desc',
        'is_show',
        'is_deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'image_ads_url',
        'ads_link'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'express_start' => 'datetime',
        'express_end' => 'datetime',
        'highlight_start' => 'datetime',
        'highlight_end' => 'datetime',
        'renew_at' => 'datetime',
    ];

    // relationships
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function reactions(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'news_like',
            'news_id',
            'user_id',
        )->withPivot('type');
    }

    public function likes(): BelongsToMany
    {
        return $this->reactions()->wherePivot('type', NewLikeTypeEnum::LIKE);
    }

    public function dislikes(): BelongsToMany
    {
        return $this->reactions()->wherePivot('type', NewLikeTypeEnum::DISLIKE);
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
                $this->getTable() . '.is_show' => 1
            ]);
    }

    public function scopeAds($query): Builder
    {
        return $query->showed()
            ->where([
                $this->getTable() . '.is_express' => 1,
                [$this->getTable() . '.express_start', '<=', now()],
                [$this->getTable() . '.express_end', '>', now()]
            ]);
    }

    public function scopeHighlight($query): Builder
    {
        return $query->showed()
            ->where([
                $this->getTable() . '.is_highlight' => 1,
                [$this->getTable() . '.highlight_start', '<=', now()],
                [$this->getTable() . '.highlight_end', '>', now()]
            ]);
    }

    public function scopeFilter($query, array $filters)
    {
        $keyword = data_get($filters, 'keyword');
        $trashed = data_get($filters, 'trashed');
        $status = data_get($filters, 'status');

        $query->when($keyword != null, function ($query) use ($keyword) {
            $query->where($this->getTable() . '.news_title', 'LIKE', '%' . $keyword . '%');
        })->when($trashed != null, function ($query) use ($trashed) {
            if ($trashed === 'with') {
                $query->withIsDeleted();
            } elseif ($trashed === 'only') {
                $query->onlyIsDeleted();
            }
        })->when($status != null, function ($query) use ($status) {
            $query->where('is_show', $status);
        });
    }

    // attributes
    public function isAds()
    {
        return $this->is_express
            && $this->express_start <= now()
            && $this->express_end > now()
                ? true
                : false;
    }

    public function isHighlight()
    {
        return $this->is_highlight
            && $this->highlight_start <= now()
            && $this->highlight_end > now()
                ? true
                : false;
    }

    public function adminCreateBy() : BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function getImageUrl(): string
    {
        return $this->image_url && File::exists(public_path(urldecode($this->image_url)))
            ? asset($this->image_url)
            : asset('/frontend/images/Tieudiem.png');
    }

    public function isLiked($userId)
    {
        return $userId && $this->likes()->wherePivot('user_id', $userId)->count()
            ? true : false;
    }

    public function isDisliked($userId)
    {
        return $userId && $this->dislikes()->wherePivot('user_id', $userId)->count()
            ? true : false;
    }
}
