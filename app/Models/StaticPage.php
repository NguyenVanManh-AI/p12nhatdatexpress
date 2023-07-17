<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected  $table = 'static_page';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_title',
        'image_url',
        'page_description',
        'page_content',
        'is_highlight',
        'page_group',
        'is_show',
        'is_deleted',
        'page_url',
        'meta_title',
        'meta_key',
        'meta_desc',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'show_order'
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
