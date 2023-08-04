<?php

namespace App\Models;

use App\Models\Admin\ProjectLocation;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected  $table='project';
    public $timestamps = false;
    protected $fillable = [
        'group_id',
        'admin_id',
        'project_name',
        'project_description',
        'project_content',
        'project_url',
        'location_descritpion',
        'utility_description',
        'ground_description',
        'legal_description',
        'payment_description',
        'area_unit_id',
        'project_scale',
        'project_unit_id',
        'project_price',
        'project_price_old',
        'price_unit_id',
        'project_rent_price',
        'project_rent_price_old',
        'project_direction',
        'list_utility',
        'project_juridical',
        'project_progress',
        'project_progress_old',
        'update_progress',
        'project_furniture',
        'project_road',
        'project_road_unit',
        'bank_sponsor',
        'prepay_percent',
        'loan_time',
        'interest_percent',
        'video',
        'image_perspective',
        'image_progress',
        'picture_utilities',
        'is_vip',
        'user_balance_id_1',
        'vip_begin',
        'vip_end',
        'is_hightlight',
        'user_balance_id_2',
        'hightlight_begin',
        'hightlight_end',
        'meta_title',
        'meta_key',
        'meta_desc',
        'is_block',
        'confirmed_status',
        'confirmed_by',
        'is_show',
        'is_deleted',
        'created_at',
        'updated_at',
        'num_word',
        'project_area_from',
        'project_area_to',
        'update_price',
        'update_rent_price',
        'report',
        'investor',
        'project_unit_rent_id',
        'image_thumbnail',
        'image_url',
        'num_view',
        'created_by',
        'updated_by',
        'rating',
        'project_servey'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }
    public function unit_sell(){
        return $this->belongsTo(Unit::class, 'price_unit_id', 'id');
    }

    public function unitSell(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'price_unit_id');
    }

    public function unitArea(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'area_unit_id');
    }

    public function unit_rent(){
        return $this->belongsTo(Unit::class, 'project_unit_rent_id', 'id');
    }
    public function unit_area(){
        return $this->belongsTo(Unit::class, 'area_unit_id', 'id');
    }
    public function unit_road(){
        return $this->belongsTo(Unit::class, 'project_road_unit', 'id');
    }
    public function unit_scale(){
        return $this->belongsTo(Unit::class, 'project_unit_id', 'id');
    }
    public function group(){
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
    public function progress(){
        return $this->belongsTo(Progress::class, 'project_progress', 'id');
    }

    public function legal(): BelongsTo
    {
        return $this->belongsTo(ProjectParam::class, 'project_juridical');
    }

    public function furniture(){
        return $this->belongsTo(Furniture::class, 'project_furniture', 'id');
    }
    public function direction(){
        return $this->belongsTo(Direction::class, 'project_direction', 'id');
    }

    public function location(): HasOne
    {
        return $this->hasOne(ProjectLocation::class);
    }

    public function new_comments(){
        return $this->hasMany( ProjectComment::class, 'project_id', 'id')
            ->whereNull('parent_id')
            ->where(['is_new' => 1, 'is_deleted' => 0]);
    }
    public function comments(){
        return $this->hasMany( ProjectComment::class, 'project_id', 'id')
            ->whereNull('parent_id')
            ->where(['is_deleted' => 0]);
    }

    public function projectComments() : HasMany
    {
        return $this->hasMany(ProjectComment::class);
        // should change to only comment table
        // return $this->morphMany(Comment::class, 'commentable');
    }

    public function total_new_comments(){
        return $this->hasMany( ProjectComment::class, 'project_id', 'id')
            ->where(['is_new' => 1, 'is_deleted' => 0]);
    }
    public function report_pending(){
        return $this->hasMany( ProjectReport::class, 'project_id', 'id')
            ->where(['confirm_status' => 0]);
    }
    public function report_project(){
        return $this->hasMany(ProjectReport::class,'project_id','id')
            ->where(function ($query){
                $query->where(function ($q){
                    $q->where('report_result','=',1)->orWhere('report_result','=',null);
                })->where('report_position',1);
            })->with('report_group');
    }

    public function report_comment_project(){
        return $this->hasManyThrough(ProjectReport::class,ProjectComment::class, 'project_id', 'project_comment_id')
            ->where(function ($query){
                $query->where(function ($q){
                    $q->where('report_result','=',1)->orWhere('report_result','=',null);
                })->where('report_position',2);
            })->with('report_group');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ProjectReport::class);
    }

    // rating
    public function total_rating(){
        return $this->hasMany(ProjectRating::class,'project_id');
    }

    public function ratings()
    {
        return $this->hasMany(ProjectRating::class);
    }

    public function isShow()
    {
        return $this->is_show && !$this->is_deleted
                ? true
                : false;
    }

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowed($query): Builder
    {
        return $query->where([
                'project.is_show' => 1,
                'project.is_deleted' => 0,
                // 'project.confirmed_status' => 1,
                // ['project.expired_date', '>', time()]
            ]);
    }

    /**
     * Attributes
     */
    public function getThumbnailUrl()
    {
        return $this->image_thumbnail ?: url('frontend/images/home/image_default_nhadat.jpg');
    }

    public function getPriceWithUnit()
    {
        $price = $this->project_price != null ? $this->project_price : $this->project_rent_price;

        return $price
            ? formatPrice($price) . ' ' . data_get($this->unitSell, 'unit_name')
            : 'Đang cập nhật';
    }

    public function getRentPriceWithUnit()
    {
        $price = $this->project_rent_price ?: null;

        return $price
            ? formatPrice($price) . ' ' . data_get($this->unitSell, 'unit_name')
            : 'Đang cập nhật';
    }

    public function getAreaLabel()
    {
        $area = '';

        if ($this->project_area_from) {
            $area .= number_format($this->project_area_from) . ' - ';
        }

        $area .= number_format($this->project_area_to) . ' m2';

        return $area;
    }

    public function getShortAddress()
    {
        return data_get($this->location, 'province.province_name');
    }

    public function getFullAddress()
    {
        $address = data_get($this->location, 'address');
        $ward = data_get($this->location, 'ward.ward_name');
        $district = data_get($this->location, 'district.district_name');
        $province = data_get($this->location, 'province.province_name');

        $fullAddressArr = array_filter([$address, $ward, $district, $province]);

        return join(', ', $fullAddressArr);
    }
}
