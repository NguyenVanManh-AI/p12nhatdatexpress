<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use App\Traits\Models\TimestampTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminMailTemplate extends Model
{
    use HasFactory,
        SoftTrashed,
        TimestampTrait,
        AdminHistoryTrait;

    public $timestamps = false;

    protected $table = 'admin_mail_template';

    protected $fillable = [
        'template_title',
        'template_content',
        'template_action',
        'is_deleted',
        'is_show',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'template_mail_title',
        'show_order',
        'is_system'
    ];

    // relationships
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by')->withIsDeleted();
    }

    /**
     * Scopes
     */
    public function scopeSystem($query, $isSystem = true)
    {
        return $query->where($this->getTable() . '.is_system', $isSystem);
    }

    public function scopeFilter($query, array $filters)
    {
        $keyword = data_get($filters, 'keyword');

        $query->when($keyword != null, function ($query) use ($keyword) {
            $query->where($this->getTable() . '.template_title', 'LIKE', '%' . $keyword . '%');
        });
    }
}
