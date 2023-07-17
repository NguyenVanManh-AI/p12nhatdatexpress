<?php

namespace App\Models\Banner;

use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Traits\Filterable;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;

class Banner extends Model
{
    use HasFactory;
    use Filterable;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected $table = 'banner';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    protected $fillable = [
        'banner_group_id',
        'group_id',
        'banner_title',
        'image_url',
        'link',
        'target_type',
        'date_from',
        'date_to',
        'user_id',
        'transaction_id',
        'is_show',
        'image_default_url',
        'is_confirm',
        'confirm_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted',
        'banner_default',
        'num_click',
        'banner_code'
    ];

    protected $filterable = [
        'keyword',
        'status',
        'time_at',
    ];

    // relationship group
    public function group(){
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function bannerGroup() : BelongsTo
    {
        return $this->belongsTo(BannerGroup::class, 'banner_group_id');
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
                // 'banner.is_confirm' => 1,
                'banner.is_deleted' => 0,
                ['banner.date_from', '<=', time()],
                ['banner.date_to', '>=', time()]
            ]);
    }

    // relationship user
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // relationship user detail
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }

    protected function filterTimeAt($query, $value)
    {
        if (!empty($value['date_start'])) {
            $date_start = strtotime($value['date_start']);
            $query = $query->where('date_from', '>=', $date_start);
        }
        if (!empty($value['date_end'])) {
            $date_end = strtotime($value['date_end']);
            $query = $query->where('date_to', '<=', $date_end);
        }
        return $query;
    }

    protected function filterStatus($query, $value)
    {
        return $query->where('is_confirm',$value);
    }

    protected function filterKeyword($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            return $query->where('banner_title', 'like', "%$value%")
                ->orWhereRelation('user', 'phone_number', 'like', "%$value%")
                ->orWhereRelation('user', 'email', 'like', "%$value%");
        });
    }
}
