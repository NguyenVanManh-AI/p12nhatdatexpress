<?php

namespace App\Models\User;

use App\Models\UserLevelStatus;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class UserLevel extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;
    
    protected  $table='user_level';
    public $timestamps = false;

    protected $fillable = [
        'level_name',
        'image_url',
        'percent_special',
        'classified_min_quantity',
        'classified_max_quantity',
        'deposit_min_amount',
        'deposit_max_amount',
        'is_show',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted'
    ];

    // relationships
    public function levelStatuses()
    {
        return $this->hasMany(UserLevelStatus::class, 'user_level');
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
}
