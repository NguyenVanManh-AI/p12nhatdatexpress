<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGuide extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_guide';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guide_type',
        'guide_title',
        'guide_content',
        'guide_url',
        'image_url',
        'is_show',
        'is_deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
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
