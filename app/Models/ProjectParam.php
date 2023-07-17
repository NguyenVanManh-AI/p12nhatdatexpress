<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectParam extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table='project_param';
    public $timestamps = false;

    protected $fillable = [
        'param_type',
        'param_code',
        'param_name',
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
}
