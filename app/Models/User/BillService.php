<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\Filterable;
use App\Traits\Models\AdminHistoryTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillService extends Model
{
    use HasFactory;
    use Filterable;
    use AdminHistoryTrait;

    protected $table = 'bill_service';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    protected $fillable = [
        'bill_type',
        'user_id',
        'transaction_id',
        'company_name',
        'company_representative',
        'tax_code',
        'company_address',
        'bill_note',
        'confirm_status',
        'confirm_by',
        'confirm_time',
        'bill_url',
        'created_at',
        'updated_at'
    ];

    protected $filterable = [
        'start_day',
        'end_day',
        'money',
        'bill_state' => 'confirm_status'
    ];

    // relationship user
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // relationship user detail
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }

    // relationship user detail
    public function transaction(){
        return $this->belongsTo(UserTransaction::class, 'transaction_id', 'id');
    }

    // relationship user detail
    public function deposit(){
        return $this->belongsTo(UserDeposit::class, 'transaction_id', 'user_transaction_id');
    }

    /**
     * Search Tình trạng Bill
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterMoney($query, $value)
    {
        return $query->whereRelation('deposit', 'deposit_amount', '=', $value);
    }

    /**
     * Filter date start
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterStartDay($query, $value)
    {
        $date_start = strtotime($value);
        return $query->where($this->table . '.' . 'created_at', '>=', $date_start);
    }

    /**
     * Filter date end
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterEndDay($query, $value)
    {
        $end_of_date = Carbon::createFromFormat('Y-m-d', $value)->endOfDay()->toDateTimeString();
        $date_end = strtotime($end_of_date);
        return $query->where($this->table . '.' . 'created_at', '<=', $date_end);
    }
}
