<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
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
        'page_name',
        'page_icon',
        'page_url',
        'is_duplicate',
        'is_page_file',
        'page_parent_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'show_order'
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'page_parent_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(self::class, 'page_parent_id');
    }
}
