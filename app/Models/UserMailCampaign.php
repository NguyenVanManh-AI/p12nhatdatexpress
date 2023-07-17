<?php

namespace App\Models;

use App\Enums\User\CampaignStatus;
use App\Models\User\Customer;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserMailCampaign extends Model
{
    use HasFactory;
    use SoftTrashed;
    use AdminHistoryTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_mail_campaign';

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
        'user_id',
        'campaign_name',
        'mail_template_id',
        'cus_status',
        'cus_source',
        'cus_job',
        'province_id',
        'date_from',
        'date_to',
        'total_mail',
        'total_receipt_mail',
        'is_birthday',
        'start_date',
        'status',
        'start_time',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'customer',
        'is_deleted'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'start_date' => 'datetime'
    ];

    /**
     * Relationships
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(UserMailTemplate::class, 'mail_template_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(UserMailCampaignDetail::class, 'campaign_id');
    }

    /**
     * Attributes
     */
    public function canEdit(): bool
    {
        if ($this->is_deleted) return false;

        if ($this->is_birthday) return true;

        // if ($this->total_receipt_mail) return false;

        return $this->status == CampaignStatus::PENDING || $this->status == CampaignStatus::ACTIVE;

        // if ($this->status == CampaignStatus::SENDED) return false;

        // if ($this->start_date < now()) return false;

        // return true;
    }

    public function getStatusLabel(): string
    {
        switch ($this->status) {
            case CampaignStatus::ACTIVE:
                return 'Chưa gửi';
                break;
            case CampaignStatus::PENDING:
                return 'Đang chờ';
                break;
            case CampaignStatus::SENDED:
                return 'Đã gửi';
                break;
            case CampaignStatus::CANCELLED:
                return 'Đã dừng';
                break;
            default:
                return '';
                break;
        }
    }

    public function getStatusClass(): string
    {
        switch ($this->status) {
            case CampaignStatus::ACTIVE:
                return 'info';
                break;
            case CampaignStatus::PENDING:
                return 'warning';
                break;
            case CampaignStatus::SENDED:
                return 'success';
                break;
            case CampaignStatus::CANCELLED:
                return 'danger';
                break;
            default:
                return '';
                break;
        }
    }

    public function isBirthday(): bool
    {
        return $this->is_birthday ? true : false;
    }

    public function isSendNow(): bool
    {
        return !$this->is_birthday && !$this->start_date;
    }
}
