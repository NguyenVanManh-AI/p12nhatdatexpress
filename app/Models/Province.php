<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Province extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table='province';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'province_code',
        'province_name',
        'province_url',
        'image_url',
        'seo',
        'region_id',
        'is_show',
        'province_type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'seo' => 'array',
    ];

    /**
     * Attributes
     */
    public function getLabel()
    {
        return $this->province_name ? 'Nhà đất ' . $this->province_name : '';
    }

    public function getSeoKey()
    {
        return data_get($this->seo, 'meta_key', $this->getSeoLabel());
    }

    public function getSeoTitle()
    {
        return data_get($this->seo, 'meta_title', $this->getSeoLabel());
    }

    public function getSeoDescription()
    {
        return data_get($this->seo, 'meta_description', $this->getSeoLabel());
    }

    public function getSeoBanner()
    {
        return $this->getImageUrl();
    }

    public function getSeoLabel()
    {
        return $this->province_name ? 'Bất động sản ' . $this->province_name : '';
    }

    public function getImageUrl()
    {
        return $this->image_url && File::exists(public_path($this->image_url))
            ? asset($this->image_url)
            : null;
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

    public function scopeFilter($query, array $filters)
    {
        $keyword = data_get($filters, 'keyword');

        $query->when($keyword != null, function ($query) use ($keyword) {
            $query->where($this->getTable() . '.province_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere($this->getTable() . '.province_url', 'LIKE', '%' . $keyword . '%')
                ->orWhere($this->getTable() . '.seo->meta_key', 'LIKE', '%' . $keyword . '%')
                ->orWhere($this->getTable() . '.seo->meta_title', 'LIKE', '%' . $keyword . '%')
                ->orWhere($this->getTable() . '.seo->meta_description', 'LIKE', '%' . $keyword . '%');
        });
    }
}
