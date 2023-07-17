<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBalance extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_balance';

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
        'package_id',
        'vip_amount',
        'highlight_amount',
        'package_from_date',
        'package_to_date',
        'classified_normal_amount',
        'created_at',
        'status'
    ];

    public function classifiedPackage() : BelongsTo
    {
        return $this->belongsTo(ClassifiedPackage::class, 'package_id');
    }
}
