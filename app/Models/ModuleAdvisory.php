<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleAdvisory extends Model
{
    use HasFactory, AdminHistoryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'advisoryable_id',
        'advisoryable_type',
        'ip',
        'fullname',
        'email',
        'phone_number',
        'note',
        'options',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    // relationships
    /**
     * Get all of the owning advisory models.
     */
    public function advisoryable()
    {
        return $this->morphTo();
    }
}
