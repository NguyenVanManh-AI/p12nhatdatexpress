<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeaturedKeyword extends Model
{
    use HasFactory,
        SoftDeletes,
        AdminHistoryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'target_id',
        'target_type',
        'views',
        'is_active',
    ];

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query): Builder
    {
        return $query->where([
                'featured_keywords.is_active' => 1,
            ]);
    }

    public function featuredable(): MorphTo
    {
        return $this->morphTo(
            'target'
        );
    }

    public function scopeFilter($query, array $filters)
    {
        $keyword = data_get($filters, 'keyword');
        $trashed = data_get($filters, 'trashed');
        $status = data_get($filters, 'status');

        $query->when($keyword != null, function ($query) use ($keyword) {
            $query->whereHasMorph('featuredable', [District::class], function ($q) use ($keyword) {
                    return $q->where('district_name', 'LIKE', '%' . $keyword . '%');
                })
                ->orWhereHasMorph('featuredable', [Group::class], function ($q) use ($keyword) {
                    return $q->where('group_name', 'LIKE', '%' . $keyword . '%');
                });
        })->when($trashed != null, function ($query) use ($trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($status != null, function ($query) use ($status) {
            $query->where('is_active', $status);
        });
    }

    // public function tags()
    // {
    //     return $this->morphTo(
    //         Tag::class,
    //         'target',
    //         'tag_relationships',
    //     );
    // }

    // public function trackings()
    // {
    //     return $this->morphMany(
    //         ModuleTracking::class,
    //         'target',
    //     );
    // }

    //    // Relationships
    //    public function target()
    //    {
    //        return $this->belongsTo($this->target_type, 'target_id');
    //    }
}
