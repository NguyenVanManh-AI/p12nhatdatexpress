<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMailConfig extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_mail_config';

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
        'mail_drive',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted',
        'is_config',
        'num_mail'
    ];
}
