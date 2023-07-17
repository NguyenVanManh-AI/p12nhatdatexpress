<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    
    protected $table = 'payment_method';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_name',
        'payment_param',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
