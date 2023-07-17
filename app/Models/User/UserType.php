<?php

namespace App\Models\User;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
	use HasFactory;
	use AdminHistoryTrait;

	protected  $table = 'user_type';
	public $timestamps = false;

	protected $fillable = [
		'type_name',
		'image_url',
		'is_show',
		'created_at',
		'created_by',
		'updated_at',
		'column_9',
		'updated_by'
	];

	/**
	 * Scope a query to only include
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeShowed($query): Builder
	{
		return $query->where([
			$this->getTable() . '.is_show' => 1,
		]);
	}
}
