<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionNew extends Model
{
    use HasFactory;
    use SoftTrashed;
    use AdminHistoryTrait;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_at',
        'created_by',
        'image',
        'is_deleted',
        'news_content',
        'news_description',
        'news_title',
        'promotion_id',
        'updated_at',
        'updated_by',
    ];
}
