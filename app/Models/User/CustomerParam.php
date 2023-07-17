<?php

namespace App\Models\User;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerParam extends Model
{
    use HasFactory;
    use SoftTrashed;
    use AdminHistoryTrait;

    protected $table = 'customer_param';

    protected $filterable = [
        'param_type',
        'param_code',
        'param_name',
        'is_deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
