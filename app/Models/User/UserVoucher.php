<?php

namespace App\Models\User;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'user_voucher';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    protected $fillable = [
      'voucher_code',
      'voucher_name',
      'amount',
      'amount_used',
      'voucher_type',
      'voucher_percent',
      'receipt_date',
      'start_date',
      'end_date',
      'user_id',
      'created_by'
    ];
}
