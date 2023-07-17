<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table='furniture';
    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'furniture_code',
        'furniture_name',
        'is_show',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
