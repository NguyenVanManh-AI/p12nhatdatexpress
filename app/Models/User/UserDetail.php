<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class UserDetail extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    
    protected $table = 'user_detail';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $fillable = [
        'user_id',
        'fullname',
        'image_url',
        'birthday',
        'business_license',
        'tax_number',
        'facebook',
        'zalo',
        'twitter',
        'youtube',
        'website',
        'intro',
        'background_url',
        'project_id',
        'banner_left',
        'banner_right',
        'intro_link',
        'phone_number',
        'email',
        'source_from',
        'personal_id',
        'banner_confirm',
        'report'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getAvatarAttribute()
    {
        return $this->image_url && File::exists(public_path($this->image_url))
            ? $this->image_url
            : '/frontend/images/personal-logo.png';
    }

    public function getExpertAvatar()
    {
        return $this->getAvatarUrl() ?: asset('/frontend/images/Avatar.png');
    }

    public function getAvatarUrl()
    {
        return $this->image_url && File::exists(public_path($this->image_url))
            ? asset($this->image_url)
            : null;
    }
}
