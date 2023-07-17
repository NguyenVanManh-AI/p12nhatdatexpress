<?php

namespace App\Models\User;

use App\Models\Banner\Banner;
use App\Models\Banner\BannerGroup;
use App\Models\User;
use App\Traits\Filterable;
use App\Traits\Models\AdminHistoryTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    use HasFactory;
    use Filterable;
    use AdminHistoryTrait;

    protected $table = 'user_transaction';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'transaction_type',
        'sevice_fee_id',
        'classified_id',
        'post_package_id',
        'banner_group_id',
        'coin_amount',
        'user_voucher_id',
        'voucher_discount_percent',
        'voucher_discount_coin',
        'user_level_percent',
        'user_level_coin',
        'total_coin_amount',
        'total_price',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    protected $filterable = [
        'transaction_type',
        'sevice_fee_id',
        'classified_id',
        'coin_amount',
        'total_coin_amount',
        'keyword',
        'status',
        'created_at',
        'created_by'
//        'time_at'
    ];

    /**
     * Filter created at
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterCreatedAt($query, $value)
    {
        if (key_exists('date_start', $value)) {
            $date_start = strtotime($value['date_start']);
            $query = $query->where($this->table . '.' . 'created_at', '>=', $date_start);
        }
        if (key_exists('date_end', $value)) {
            $end_of_date = Carbon::createFromFormat('Y-m-d', $value['date_end'])->endOfDay()->toDateTimeString();
            $date_end = strtotime($end_of_date);
            $query = $query->where($this->table . '.' . 'created_at', '<=', $date_end);
        }
        return $query;
    }

    /**
     * Search Tình trạng banner
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterStatus($query, $value)
    {
        if($value != -1) {
            return $query->whereRelation('banner', 'date_to', '>', time())
                         ->whereRelation('banner', 'is_confirm', '=', $value);
        }else{
            return $query->whereRelation('banner', 'date_to', '<', time());
        }
    }

    /**
     * Search Status
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterKeyword($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            return $query->whereRelation('banner', 'banner_title', 'like', "%$value%")
                ->orWhereRelation('user', 'phone_number', 'like', "%$value%")
                ->orWhereRelation('user', 'email', 'like', "%$value%");
        });
    }


    // relationship user
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // relationship user detail
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }

    // relationship banner group
    public function banner_group(){
        return $this->belongsTo(BannerGroup::class, 'banner_group_id', 'id');
    }

    // relationship user deposit
    public function user_deposit(){
        return $this->belongsTo(UserDeposit::class, 'id', 'user_transaction_id');
    }

    // relationship user banner
    public function banner(){
        return $this->belongsTo(Banner::class, 'id', 'transaction_id');
    }

}
