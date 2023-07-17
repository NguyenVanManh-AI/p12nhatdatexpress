<?php

namespace App\Models\Classified;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassifiedLocation extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    protected $table = 'classified_location';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'classified_id',
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'map_longtitude',
        'map_latitude'
    ];

    public function province(){
        return $this->belongsTo(Province::class,'province_id');
    }
    public function district(){
        return $this->belongsTo(District::class,'district_id');
    }
    public function ward(){
        return $this->belongsTo(Ward::class, 'ward_id', 'id');
    }

    public function classified()
    {
        return $this->belongsTo(Classified::class);
    }
}
