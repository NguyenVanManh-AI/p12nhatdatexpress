<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'direction';
    public $timestamps = false;

    protected $fillable = [
        'direction_name',
        'image_url',
        'show_order',
        'is_show'
    ];

    public function scopeShowed($query): Builder
    {
        return $query->where([
                $this->getTable() . '.is_show' => 1,
            ]);
    }
}
