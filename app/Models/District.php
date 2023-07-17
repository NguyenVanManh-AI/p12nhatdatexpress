<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class District extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    protected  $table='district';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'district_name',
        'district_url',
        'image_url',
        'province_id',
        'is_show',
        'show_order',
        'district_type'
    ];

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if(isset($this->attributes[$key])){
            if ($key == 'district_name'){
//                if ($this->attributes['district_type'] == 0){
//                    return 'Quận ' . $value;
//                }
//                else if ($this->attributes['district_type'] == 2){
//                    return 'Thị xã ' . $value;
//                }else{
//                    return 'Huyện ' . $value;
//                }
                if ($this->attributes['district_type'] == 0 && is_numeric($this->attributes['district_name'])){
                    return 'Quận ' . $value;
                }

            }
        }

        return $value;
    }
    public function province(){
        return $this->belongsTo(Province::class,'province_id');
    }

    public function featuredKeywords(): MorphMany
    {
        return $this->morphMany(
            FeaturedKeyword::class,
            'target',
        );
    }

    public function getNameLabel()
    {
        return $this->district_name;
    }

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowed($query): Builder
    {
        return $query->where([
                $this->getTable() . '.is_show' => 1,
            ]);
    }
}
