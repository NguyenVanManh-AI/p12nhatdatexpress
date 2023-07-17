<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'properties_type',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at'
    ];
}
