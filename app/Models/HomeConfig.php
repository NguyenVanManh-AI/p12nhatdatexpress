<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class HomeConfig extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'home_config';

    public $timestamps = false;

    protected $fillable = [
        'header_text_block',
        'desktop_header_image',
        'mobile_header_image',
        'num_access_fake',
        'num_most_viewed',
        'home_link',
        'num_classified',
        'home_url',
        'meta_title',
        'meta_key',
        'meta_desc',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'popup_image',
        'popup_status',
        'popup_time',
        'popup_link',
        'popup_time_expire'
    ];

    // Attributes
    public function getSeoBanner()
    {
        return $this->image_banner && File::exists(public_path('/system/img/home-config/', $this->desktop_header_image))
            ? asset('/system/img/home-config/' . $this->desktop_header_image)
            : asset('/frontend/images/home/image_default_nhadat.jpg');
    }
}
