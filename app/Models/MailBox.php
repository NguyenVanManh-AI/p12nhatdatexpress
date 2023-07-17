<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailBox extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected  $table = 'mailbox';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'object_type',
        'parent_id',
        'mail_title',
        'mail_content',
        'user_id',
        'send_time',
        'mailbox_type',
        'mailbox_status',
        'is_deleted',
        'mailbox_pin',
        'created_by'
    ];
}
