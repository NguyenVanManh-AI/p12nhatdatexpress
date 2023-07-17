<?php

namespace App\Models\Event;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLocation extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table='event_location';
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

    public function ward(){
        return $this->belongsTo(Ward::class, 'ward_id', 'id');
    }
}
