<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordUse extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected  $table='keyword_use';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keyword',
        'number_use',
        'keyword_type',
    ];
}
