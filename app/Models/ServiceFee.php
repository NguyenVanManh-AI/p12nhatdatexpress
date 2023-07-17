<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFee extends Model
{
    use HasFactory;

    protected $table = 'service_fee';
    public $timestamps = false;

    protected $fillable = [
        'service_name',
        'service_coin',
        'service_discount_coin',
        'existence_time',
        'service_unit',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
