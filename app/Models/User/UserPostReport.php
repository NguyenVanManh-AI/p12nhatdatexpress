<?php

namespace App\Models\User;

use App\Models\ReportGroup;
use App\Models\User;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPostReport extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'user_post_report';
    protected $fillable = [
        'user_id',
        'report_type',
        'user_post_id',
        'report_content',
        'reporter',
        'phone',
        'email',
        'address',
        'report_result',
        'confirm_note',
        'confirm_status',
        'confirm_by',
        'report_time',
        'report_position',
        'user_post_comment_id',
        'personal_id'
    ];
    public $timestamps = false;
    public function report_group(){
        return $this->belongsTo(ReportGroup::class, 'report_type', 'id');
    }

    public function user_post(){
        return $this->belongsTo(UserPost::class);
    }

    public function userPost(): BelongsTo
    {
        return $this->belongsTo(UserPost::class);
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'personal_id');
    }

    public function user_post_comment(){
        return $this->belongsTo(UserPostComment::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query, $result = true)
    {
        return $query->where($this->getTable() . '.report_result', $result);
    }
}
