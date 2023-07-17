<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMailCampaign extends Model
{
    use HasFactory;
    use SoftTrashed;
    use AdminHistoryTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_mail_campaign';

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
        'campaign_name',
        'mail_template_id',
        'campaign_date_from',
        'campaign_date_to',
        'total_mail',
        'total_receipt_mail',
        'is_birthday',
        'start_date',
        'start_time',
        'province_id',
        'district_id',
        'job_id',
        'cus_source',
        'account_type',
        'created_from',
        'created_to',
        'group_id',
        'group_parent_id',
        'birthday',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted',
        'is_action',
        'is_customer'
    ];
}
