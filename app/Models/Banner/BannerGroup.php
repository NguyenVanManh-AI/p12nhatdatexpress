<?php

namespace App\Models\Banner;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerGroup extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'banner_group';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    protected $fillable = [
        'banner_group',
        'banner_group_name',
        'banner_permission',
        'banner_name',
        'banner_position',
        'is_child',
        'banner_description',
        'banner_width',
        'banner_height',
        'banner_coin_price',
        'is_show',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'banner_type',
        'banner_price'
    ];

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
