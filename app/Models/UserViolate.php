<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserViolate extends Model
{
    use HasFactory,
        SoftDeletes;
    use AdminHistoryTrait;

    protected $fillable = [
        'user_id',
        'target_id',
        'target_type',
        'action',
        'action_url',
        'status',
        'options'
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
