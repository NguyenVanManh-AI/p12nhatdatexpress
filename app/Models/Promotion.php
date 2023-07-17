<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    use SoftTrashed;
    use AdminHistoryTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotion';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'promotion_type',
        'promotion_code',
        'value',
        'promotion_unit',
        'num_use',
        'is_all',
        'user_id_use',
        'date_from',
        'date_to',
        'is_show',
        'is_deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'used',
        'user_get',
        'is_private',
    ];
}
