<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagePermission extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    
    protected $table = 'page_permission';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_type',
        'permission_name',
        'is_file',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'is_duplicate'
    ];
}
