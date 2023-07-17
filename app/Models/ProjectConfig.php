<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectConfig extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'project_config';
    public $timestamps = false;

    protected $fillable = [
        'type_config',
        'description',
        'image',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
