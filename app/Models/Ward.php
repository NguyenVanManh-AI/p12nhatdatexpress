<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    protected  $table = 'ward';
    public $timestamps = false;

    protected $fillable = [
        'ward_name',
        'ward_url',
        'image_url',
        'district_id',
        'ward_type',
        'is_show',
        'show_order'
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
            if ($key == 'ward_name'){
                if ($this->attributes['ward_type'] == 1 && is_numeric($this->attributes['ward_name'])){
                    return 'Phường ' . $value;
                }

            }
        }

        return $value;
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
