<?php

namespace App\Models\Classified;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassifiedParam extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'classified_param';
    public $timestamps = false;

    protected $fillable = [
        'param_type',
        'param_code',
        'param_name',
        'is_show',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
