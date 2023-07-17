<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRequest extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_request';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_name',
        'investor',
        'address',
        'ward_id',
        'district_id',
        'province_id',
        'confirmed_status',
        'confirmed_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'user_id'
    ];
}
