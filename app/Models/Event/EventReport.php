<?php

namespace App\Models\Event;

use App\Models\ReportGroup;
use App\Traits\Filterable;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReport extends Model
{
    use HasFactory;
    use Filterable;
    use AdminHistoryTrait;

    protected $table = 'event_report';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $fillable = [
        'user_id',
        'report_type',
        'event_id',
        'report_content',
        'reporter',
        'phone',
        'email',
        'address',
        'confirm_note',
        'report_result',
        'confirm_status',
        'confirm_by',
        'report_time'
    ];

    protected $filterable = [
        'keyword',
        'id',
        'start_date',
        'created_by',
        'is_confirmed',
        'province_id',
        'district_id',
        'is_highlight'
    ];

    // Relationship
    public function event(){
        return $this->belongsTo(Event::class);
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
