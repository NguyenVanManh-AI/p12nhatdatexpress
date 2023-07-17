<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectReport extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'project_report';
    protected $fillable = [
        'user_id',
        'report_type',
        'report_position',
        'project_id',
        'project_comment_id',
        'report_content',
        'reporter',
        'phone',
        'email',
        'address',
        'report_result',
        'confirm_note',
        'confirm_status',
        'confirm_by',
        'report_time'
    ];
    public $timestamps = false;

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function project_comment(){
        return $this->belongsTo(ProjectComment::class);
    }

    public function report_group(){
        return $this->belongsTo(ReportGroup::class, 'report_type', 'id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query, $result = true)
    {
        return $query->where($this->getTable() . '.report_result', $result);
    }
}
