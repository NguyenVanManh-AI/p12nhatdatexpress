<?php

namespace App\Models\Classified;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassifiedLikeComment extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'classified_like_comment';
    public $timestamps = false;
}
