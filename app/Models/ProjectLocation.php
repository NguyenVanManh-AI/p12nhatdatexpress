<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLocation extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table='project_location';

    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'map_longtitude',
        'map_latitude'
    ];
}
