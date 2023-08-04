<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

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

    // relationships
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class)->withIsDeleted();
    }

    public function scopeFilter($query, array $filters)
    {
        $keyword = data_get($filters, 'keyword');

        $query->when($keyword != null, function ($query) use ($keyword) {
            $query->where($this->getTable() . '.news_title', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('promotion', function($query) use ($keyword) {
                    $query->where('promotion_code', 'like', '%' . $keyword . '%');
                });
        });
    }

    public function getImageUrl()
    {
        $image = 'system/images/post_promotion/'. $this->image;

        return $this->image && File::exists(public_path($image))
            ? asset($image)
            : asset('frontend/images/managerEmployee/offer.png');
    }
}
