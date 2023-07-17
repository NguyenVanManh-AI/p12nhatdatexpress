<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMailConfig extends Model
{
    use HasFactory;
    use SoftTrashed;
    use AdminHistoryTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_mail_config';

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
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encription', // ? encryption
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'num_mail',
        'is_deleted',
        'column_name'
    ];
}
