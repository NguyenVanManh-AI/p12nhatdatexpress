<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminConfig extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'admin_config';

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'config_type',
        'config_code',
        'config_name',
        'config_value',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
