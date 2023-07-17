<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLikeComment extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'project_like_comment';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'comment_id'
    ];
}
