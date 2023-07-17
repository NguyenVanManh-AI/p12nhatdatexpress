<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'progress';
    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'progress_code',
        'progress_name',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
