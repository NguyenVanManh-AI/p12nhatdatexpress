<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForbiddenWord extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected  $table = 'forbidden_word';

    public $timestamps = false;

    protected $fillable = [
        'forbidden_word',
        'is_show',
        'is_deleted',
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
