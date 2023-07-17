<?php

namespace App\Models\Admin;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLocation extends Model
{
    use HasFactory;

    protected  $table='project_location';
    public $timestamps = false;
    protected $fillable = [
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'map_longtitude',
        'map_latitude'
    ];
    public function province(){
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
    public function district(){
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
}
