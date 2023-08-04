<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    use SoftTrashed;
    use AdminHistoryTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotion';

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
        'promotion_type',
        'promotion_code',
        'value',
        'promotion_unit',
        'num_use',
        'is_all',
        'user_id_use',
        'date_from',
        'date_to',
        'is_show',
        'is_deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'used',
        'user_get',
        'is_private',
    ];

    // attributes
    public function getSelectLabel()
    {
        return '[Mã: ' . $this->promotion_code . '] ' . ($this->promotion_type ? 'Tặng' : 'Giảm') . ': ' . $this->value . '% - Số lượng mã: ' . $this->user_get . '/' . $this->num_use . ' - Ngày khả dụng: ' . Carbon::parse($this->date_from)->format('d/m/Y') . 'đến ' . Carbon::parse($this->date_to)->format('d/m/Y');
    }

    public function isExpired()
    {
        return $this->date_to < time();
    }

    public function isOutOfUse()
    {
        return $this->used >= $this->num_use;
    }

    public function isShow()
    {
        return $this->is_show ? true : false;
    }

    public function canUse()
    {
        return !$this->isExpired()
            && !$this->isOutOfUse()
            && $this->isShow();
    }

    // scopes
    public function scopeFilter($query, array $filters)
    {
        $keyword = data_get($filters, 'keyword');

        $query->when($keyword != null, function ($query) use ($keyword) {
            $query->where($this->getTable() . '.promotion_code', 'LIKE', '%' . $keyword . '%');
        });
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
