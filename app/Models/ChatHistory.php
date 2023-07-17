<?php

namespace App\Models;

use App\Models\User\UserDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    use HasFactory;
    protected $table = 'chat_history';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function user_detail(){
        return $this->belongsTo(UserDetail::class,'user_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
}
