<?php

namespace App\Models;

use App\Enums\HistoryAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class History extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'historyable_id',
        'historyable_type',
        'attributes',
        'action',
        'action_user_id',
        'action_admin_id'
    ];

    protected $casts = [
        'attributes' => 'array',
        'created_at' => 'datetime'
    ];

    // relationships
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'action_admin_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_user_id');
    }

    public function historyable(): MorphTo
    {
        return $this->morphTo();
        // return $this->morphTo('historyable', 'historyable_type', 'historyable_id');
    }

    /**
     * Scope
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where($this->getTable() . '.historyable_type', 'LIKE', '%'.$search.'%')
                ->orWhere($this->getTable() . '.historyable_id', 'LIKE', '%'.$search.'%');
            });
        })->when($filters['action'] ?? null, function ($query, $action) {
            $query->where($this->getTable() . '.action', $action);
        });
    }

    // attributes
    public function getActionLabel()
    {
        $label = $this->action;

        switch ($this->action) {
            case HistoryAction::CREATED:
                $label = 'Thêm mới';
                break;
            case HistoryAction::UPDATED:
                $label = 'Cập nhật';
                break;
            case HistoryAction::DELETED:
                $label = 'Xóa tạm thời';
                break;
            case HistoryAction::RESTORED:
                $label = 'Khôi phục';
                break;
            case HistoryAction::FORCE_DELETED:
                $label = 'Xóa hẳn';
                break;
            default:
                break;
        }

        return $label;
    }

    /**
     * Boot
     */
    public static function boot()
    {
        parent::boot();

        // disable updated_at
        static::creating(function ($model) {
            $model->created_at = $model->created_at ?: $model->freshTimestamp();
        });
    }
}
