<?php

namespace App\Models\Classified;

use App\Models\ReportGroup;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassifiedReport extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    protected $table = 'classified_report';
    protected $fillable = [
        'user_id',
        'report_type',
        'report_position',
        'classified_id',
        'classified_comment_id',
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
    public function classified(){
        return $this->belongsTo(Classified::class);
    }

    public function classified_comment(){
        return $this->belongsTo(ClassifiedComment::class);
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
