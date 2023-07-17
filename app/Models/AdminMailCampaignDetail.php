<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMailCampaignDetail extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_mail_campaign_detail';

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
        'admin_campaign_id',
        'admin_mail_config_id',
        'user_id',
        'user_email',
        'receipt_status',
        'receipt_time',
        'created_by',
        'created_at'
    ];
}
