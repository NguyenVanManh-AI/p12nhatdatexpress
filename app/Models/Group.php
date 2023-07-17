<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Classified\Classified;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\File;

class Group extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected $table = 'group';
    public $timestamps = false;

    protected $fillable = [
        'group_name',
        'group_url',
        'image_url',
        'group_type',
        'parent_id',
        'image_banner',
        'image_banner_mobile',
        'meta_title',
        'meta_key',
        'meta_desc',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'group_description',
        'is_deleted'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function classified(): HasMany
    {
        return $this->hasMany(Classified::class, 'group_id');
    }

    public function featuredKeywords(): MorphMany
    {
        return $this->morphMany(
            FeaturedKeyword::class,
            'target',
        );
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'dependence_group',
            'group_id',
            'dependence_id',
        );
    }

    public function getNameLabel(): string
    {
        return $this->group_name;
    }

    public function getSEOBanner(): string
    {
        // maybe not have banner get parent banner by recursive
        if ($this->image_banner && File::exists(public_path($this->image_banner)))
            return asset($this->image_banner);

        $homeConfig = HomeConfig::select('desktop_header_image')->first();

        // should remove default prefix system/img/home-config. add prefix when save config.
        return data_get($homeConfig, 'desktop_header_image')
            ? asset('/system/img/home-config/' . data_get($homeConfig, 'desktop_header_image'))
            : asset('/frontend/images/home/image_default_nhadat.jpg');
    }

    /**
     * Get full url for group | multiple levels
     */
    // public function getFullGroupUrl()
    // {
    //     $groupUrl = $this->group_url;
    //     $parent = $this->parent;

    //     while(!is_null($parent)) {
    //         if (data_get($parent, 'group_url')) {
    //             $groupUrl = data_get($parent, 'group_url') . '/' . $groupUrl;
    //         }
    //         $parent = $parent->parent;
    //     }

    //     return $groupUrl;
    // }

    public function getAncestorId(): int
    {
        $groupId = $this->id;
        $parent = $this->parent;

        while(!is_null($parent)) {
            if (data_get($parent, 'id')) {
                $groupId = data_get($parent, 'id');
            }
            $parent = $parent->parent;
        }

        return $groupId;
    }

    public function getLastParentGroup(): string
    {
        $groupUrl = $this->group_url;
        $parent = $this->parent;

        while(!is_null($parent)) {
            if (data_get($parent, 'group_url')) {
                $groupUrl = data_get($parent, 'group_url');
            }
            $parent = $parent->parent;
        }

        return $groupUrl;
    }

    /**
     * Get two level url for group | only 2 levels
     */
    public function getTwoLevelGroupUrl(): string
    {
        $groupUrl = $this->group_url;
        $parent = $this->parent;
        $parentUrl = null;

        while(!is_null($parent)) {
            if (data_get($parent, 'group_url')) {
                $parentUrl = data_get($parent, 'group_url');
            }
            $parent = $parent->parent;
        }

        return $parentUrl
            ? $parentUrl . '/' . $groupUrl
            : $groupUrl;
    }

    /**
     * Get full array of all parent group id
     * @return array
     */
    public function getParentGroupIds(): array
    {
        $ids = [$this->id];
        $parent = $this->parent;

        while(!is_null($parent)) {
            $ids[] = data_get($parent, 'id');
            $parent = $parent->parent;
        }

        return $ids;
    }

    /**
     * Get full array of all children group id
     * @return array
     */
    public function getChildrenGroupIds() : array
    {
        $ids = [$this->id];

        foreach ($this->allChildren as $child) {
            $ids[] = $child->id;

            $ids = array_unique(array_merge($ids, $child->getChildrenGroupIds()));
        }

        return $ids;
    }
}
