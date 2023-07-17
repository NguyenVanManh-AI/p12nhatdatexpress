<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassifiedPackage extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected $table = 'classified_package';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_type',
        'package_name',
        'price',
        'discount_price',
        'duration_time',
        'vip_amount',
        'vip_duration',
        'highlight_amount',
        'highlight_duration',
        'classified_nomal_amount',
        'classified_per_day',
        'cus_mana',
        'data_static',
        'image_url',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'qr_image',
        'best_seller',
        'coin_price',
        'discount_coin_price',
        'status'
    ];

    public function userBalances(): HasMany
    {
        return $this->hasMany(UserBalance::class, 'package_id');
    }
}
