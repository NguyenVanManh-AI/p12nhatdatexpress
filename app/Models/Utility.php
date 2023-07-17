<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Utility extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table='utility';
    public $timestamps = false;

    protected $fillable = [
        'utility_name',
        'image_url',
        'show_order',
        'is_show',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
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

    public function getImageUrl()
    {
        return $this->image_url && File::exists(public_path($this->image_url))
            ? asset($this->image_url)
            : null;
    }
}
