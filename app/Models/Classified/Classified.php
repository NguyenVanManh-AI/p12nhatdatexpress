<?php

namespace App\Models\Classified;

use App\Models\Direction;
use App\Models\Furniture;
use App\Models\Group;
use App\Models\ModuleAdvisory;
use App\Models\Progress;
use App\Models\Unit;
use App\Models\User;
use App\Models\Project;
use App\Models\User\Customer;
use App\Models\User\UserDetail;
use App\Models\UserBalance;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\File;

class Classified extends Model
{
    use HasFactory;
    use SoftTrashed;
    use AdminHistoryTrait;

    protected $table = 'classified';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'classified_code',
        'group_id',
        'user_id',
        'project_id',
        'classified_name',
        'classified_description',
        'classified_url',
        'is_monopoly',
        'is_broker',
        'classified_area',
        'area_unit_id',
        'classified_price',
        'price_unit_id',
        'classified_direction',
        'list_utility',
        'num_bed',
        'num_toilet',
        'num_people',
        'classified_juridical',
        'classified_progress',
        'classified_furniture',
        'is_mezzanino',
        'is_internet',
        'is_balcony',
        'is_freezer',
        'video',
        'image_perspective',
        'image_thumbnail',
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
        'contact_name',
        'contact_phone',
        'contact_email',
        'contact_address',
        'ip',
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
        'expired_date',
        'renew_at',
        'unapproved_at',
        'user_balance_id',
        'num_share',
        'advance_stake',
        'num_view_today',
        'num_view',
        'report',
        'rating',
        'active_date',
        'fullname',
        'email',
        'phone_number',
        'address',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'renew_at' => 'datetime',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user_detail()
    {
        return $this->belongsTo(UserDetail::class, 'user_id', 'user_id');
    }

    public function userDetail(): BelongsTo
    {
        return $this->belongsTo(UserDetail::class, 'user_id', 'user_id');
    }

    public function report_classified()
    {
        return $this->hasMany(ClassifiedReport::class, 'classified_id', 'id')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('report_result', '=', 1)->orWhere('report_result', '=', null);
                })->where('report_position', 1);
            })->with('report_group');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ClassifiedReport::class,);
    }

    public function unit_price()
    {
        return $this->belongsTo(Unit::class, 'price_unit_id');
    }

    public function unit_area()
    {
        return $this->belongsTo(Unit::class, 'area_unit_id');
    }

    public function location()
    {
        return $this->hasOne(ClassifiedLocation::class, 'classified_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function bed()
    {
        return $this->belongsTo(ClassifiedParam::class, 'num_bed');
    }

    public function toilet()
    {
        return $this->belongsTo(ClassifiedParam::class, 'num_toilet');
    }

    public function progress()
    {
        return $this->belongsTo(Progress::class, 'classified_progress');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'classified_direction');
    }

    public function juridical()
    {
        return $this->belongsTo(ClassifiedParam::class, 'classified_juridical');
    }

    public function furniture()
    {
        return $this->belongsTo(Furniture::class, 'classified_furniture');
    }

    public function people()
    {
        return $this->belongsTo(ClassifiedParam::class, 'num_people');
    }

    public function advance()
    {
        return $this->belongsTo(ClassifiedParam::class, 'advance_stake');
    }

    // rating
    public function total_rating()
    {
        return $this->hasMany(ClassifiedRating::class, 'classified_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(ClassifiedRating::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ClassifiedComment::class);
        // should change to only comment table
        // return $this->morphMany(Comment::class, 'commentable'); 
    }

    public function userBalance(): BelongsTo
    {
        return $this->belongsTo(UserBalance::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function advisories(): MorphMany
    {
        return $this->morphMany(ModuleAdvisory::class, 'advisoryable');
    }

    /**
     * Attributes
     */
    public function getThumbnailUrl()
    {
        if ($this->image_thumbnail && File::exists(public_path($this->image_thumbnail))) {
            return asset($this->image_thumbnail);
        }

        $thumbnailPath = null;

        $images = json_decode($this->image_perspective, true) ?: [];
        foreach ($images as $image) {
            if ($image && File::exists(public_path($image))) {
                $thumbnailPath = asset($image);
                break;
            }
        }

        return $thumbnailPath;
    }

    public function getSeoBanner()
    {
        $this->getThumbnailUrl();
    }

    /**
     * get all images asset url
     * @return array
     */
    public function getImagesUrl()
    {
        if (!$this->image_perspective) return [];

        $results = [];
        $images = json_decode($this->image_perspective) ?: [];

        foreach ($images as $image) {
            if ($image && File::exists(public_path($image)))
                $results[] = asset($image);
        }

        return $results;
    }

    public function getName()
    {
        return data_get($this->user, 'fullname', $this->contact_name);
    }

    public function getPriceWithUnit()
    {
        return $this->classified_price != null
            ? formatPrice($this->classified_price) . ' ' . data_get($this->unit_price, 'unit_name')
            : 'Liên hệ';
    }

    public function getAreaLabel()
    {
        $label = '';

        if ($this->classified_area)
            $label .= $this->classified_area;

        $label .= data_get($this->unit_area, 'unit_name');

        return $label;
    }

    public function getShortAddress()
    {
        return data_get($this->location, 'province.province_name');
    }
    
    public function getFullAddress($fields = [])
    {
        $address = $ward = $district = $province = null;

        if (!$fields || in_array('address', $fields))
            $address = data_get($this->location, 'address');
        if (!$fields || in_array('ward', $fields))
            $ward = data_get($this->location, 'ward.ward_name');
        if (!$fields || in_array('district', $fields))
            $district = data_get($this->location, 'district.district_name');
        if (!$fields || in_array('province', $fields))
            $province = data_get($this->location, 'province.province_name');

        $fullAddressArr = array_filter([$address, $ward, $district, $province]);

        return join(', ', $fullAddressArr);
    }

    public function getShowUrl()
    {
        return $this->group && $this->classified_url && $this->isShow()
            ? route('home.classified.detail', [$this->group->getLastParentGroup(), $this->classified_url])
            : null;
    }

    public function canRenew()
    {
        if (
            $this->is_deleted
            || ($this->confirmed_status != 1 && $this->confirmed_status != 0) // return false nếu status không phải chờ duyệt hoặc đã duyệt
            || $this->expired_date < time()
        ) {
            return false;
        }

        return true;
    }

    public function canEdit()
    {
        if (
            $this->is_deleted
            || $this->confirmed_status == 2 // không duyệt
            || $this->expired_date < time()
        ) {
            return false;
        }

        return true;
    }

    public function canUpgrade()
    {
        if (
            $this->is_deleted
            || ($this->confirmed_status != 1 && $this->confirmed_status != 0) // không duyệt hoặc chứa từ cấm
            || $this->expired_date < time()
        ) {
            return false;
        }

        return true;
    }

    public function isVip()
    {
        return $this->is_vip
            && $this->vip_begin <= time()
            && $this->vip_end > time()
            && $this->expired_date > time()
                ? true
                : false;
    }

    public function isHighLight()
    {
        return $this->is_hightlight
            && $this->hightlight_begin <= time()
            && $this->hightlight_end > time()
            && $this->expired_date > time()
                ? true
                : false;
    }

    public function isShow()
    {
        return $this->is_show
            && !$this->is_deleted
            && $this->confirmed_status == 1
            && $this->expired_date > time()
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
                $this->getTable() . '.is_show' => 1,
                $this->getTable() . '.is_block' => 0,
                $this->getTable() . '.is_deleted' => 0,
                $this->getTable() . '.confirmed_status' => 1,
                [$this->getTable() . '.expired_date', '>', time()]
            ]);
    }

    public function scopeIsDeleted($query)
    {
        return $query->where('is_deleted',0);
    }
}
