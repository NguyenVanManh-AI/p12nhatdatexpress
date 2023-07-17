<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLocation extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table = 'customer_location';
    public $timestamps = false;

    protected $fillable = [
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'map_longtitude',
        'map_latitude'
    ];

    /**
     * Relationships
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Attributes
     */
    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . data_get($this->district, 'district_name') . ', ' . data_get($this->province, 'province_name');
    }
}
