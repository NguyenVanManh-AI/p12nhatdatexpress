<?php

namespace App\Models\User;

use App\Models\User;
use App\Models\UserCoinRefReceipt;
use App\Traits\Filterable;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserDeposit extends Model
{
    use HasFactory;
    use Filterable;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected $table = 'user_deposit';
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
        'is_transferred',
        'deposit_time',
        'deposit_type',
        'payment_method_id',
        'deposit_code',
        'is_deposited',
        'deposit_amount',
        'user_transaction_id',
        'deposit_note',
        'payment_image_url',
        'is_confirm',
        'confirm_by',
        'confirm_time',
        'deposit_status',
        'options',
        'one_time_confirm_token',
        'is_deleted'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    protected $filterable = [
        'start_day',
        'end_day',
        'money' => 'deposit_amount',
        'bill_state'
    ];

    // relationship user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userCoinRefReceipt(): HasOne
    {
        return $this->hasOne(UserCoinRefReceipt::class);
    }

    // relationship user detail
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }

    // relationship user banner
    public function bill_service(){
        return $this->belongsTo(BillService::class, 'id', 'transaction_id');
    }

    /**
     * Filter start date
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterStartDay($query, $value)
    {
        return $query->where($this->table . '.' . 'deposit_time', '>=', strtotime($value));
    }

    /**
     * Filter start date
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterEndDay($query, $value)
    {
        $end_of_date = Carbon::createFromFormat('Y-m-d', $value)->endOfDay()->toDateTimeString();
        return $query->where($this->table . '.' . 'deposit_time', '<=', strtotime($end_of_date));
    }

    /**
     * Search Tình trạng Bill
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterBillState($query, $value)
    {
        if ($value == 0){
            return $query->whereRelation('bill_service', 'confirm_status', '=', 0)->orDoesntHave('bill_service');
        }
        return $query->whereRelation('bill_service', 'confirm_status', '=', $value);
    }

}
