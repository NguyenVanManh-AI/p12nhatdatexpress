<?php

namespace App\Models\Chat;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempChat extends Model
{
    use HasFactory;
    protected $table = 'temp_chat';
    public $timestamps = false;

    protected $fillable = [
      'chat_message',
      'chat_code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->chat_time = time();
        });
    }

    public function getChatTimeAttribute($date)
    {
        return date('H:i d/m/Y', $date);
    }


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class,'user_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
}
