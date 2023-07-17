<?php

namespace App\Models;

use App\Enums\NotifyStatus;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notify extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AdminHistoryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'icon',
        'content',
        'status',
        'read',
    ];

    // Attributes
    public function getIconAttributes($value)
    {
        return $value ?: 'fas fa-file';
    }

    // Scopes
    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus(Builder $query, $status = NotifyStatus::PUBLISHED): Builder
    {
        return $query->where($this->getTable() . '.status', $status);
    }

    public function scopeRead(Builder $query, $read = true): Builder
    {
        return $query->where($this->getTable() . '.read', $read);
    }
}
