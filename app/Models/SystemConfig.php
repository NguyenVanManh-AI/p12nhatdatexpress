<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table = 'system_config';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logo',
        'line_page_manage',
        'line_page',
        'history_log',
        'hotline',
        'video_demo',
        'is_building',
        'type_popup',
        'time_system',
        'facebook',
        'youtube',
        'linkedlin',
        'image_social_default',
        'mail_notification',
        'mail_new_post',
        'mail_deposit',
        'mail_express',
        'google_map',
        'post_fake',
        'header',
        'body',
        'footer',
        'logo1',
        'logo2',
        'logo3',
        'logo4',
        'banner',
        'ch_play',
        'apple_store',
        'text_footer',
        'introduce',
        'info_company',
        'updated_at',
        'updated_by'
    ];
}
