<?php

namespace App\Models\Classified;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassifiedRating extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    protected $table = 'classified_rating';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'star',
        'classified_id',
        'rating_time',
        'user_id',
        'ip',
    ];
}
