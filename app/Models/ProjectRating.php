<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRating extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    public $timestamps = false;
    protected $table = 'project_rating';

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
