<?php

namespace App\Models;

use App\Models\User\Customer;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMailCampaignDetail extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_mail_campaign_detail';

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
        'campaign_id',
        'user_mail_config_id',
        'cus_id',
        'cus_mail',
        'receipt_status',
        'receipt_time'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Relationships
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cus_id');
    }

    public function userMailCampaign(): BelongsTo
    {
        return $this->belongsTo(UserMailCampaign::class, 'campaign_id');
    }

    public function userMailConfig(): BelongsTo
    {
        return $this->belongsTo(UserMailConfig::class);
    }

    /**
     * Attributes
     */
    public function getStatusLabel(): string
    {
        switch ($this->receipt_status) {
            case 0:
                return 'Chờ gửi';
                break;
            case 1:
                return 'Thành công';
                break;
            case 3:
                return 'Đã hủy đăng ký';
                break;
            default:
                return 'Gửi lỗi';
                break;
        }
    }
}
