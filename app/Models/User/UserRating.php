<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRating extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table ='user_rating';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'one_star',
        'two_star',
        'three_star',
        'four_star',
        'five_start',
        'created_at',
        'rating_type',
        'rating_user_id',
        'star'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class,);
    }

    public function user_detail() : BelongsTo
    {
        return $this->belongsTo(User\UserDetail::class);
    }
}
