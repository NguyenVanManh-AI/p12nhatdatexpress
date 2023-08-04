<?php

namespace App\Models\Event;

use App\Models\User;
use App\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\ModuleAdvisory;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\File;

class Event extends Model
{
    use HasFactory;
    use Filterable;
    use SoftTrashed;
    use AdminHistoryTrait;

    protected $table = 'event';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $fillable = [
        'group_id',
        'user_id',
        'admin_id',
        'event_title',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'event_content',
        'image_header_url',
        'image_invitation_url',
        'event_url',
        'is_highlight',
        'highlight_end',
        'is_confirmed',
        'is_block',
        'is_deleted',
        'meta_title',
        'meta_key',
        'meta_desc',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'take_place',
        'num_view'
    ];

    protected $filterable = [
        'keyword',
        'group_id',
        'start_date',
        'created_by',
        'is_confirmed',
        'province_id',
        'district_id',
        'is_highlight'
    ];

    // Relationship
    public function bussiness(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bussiness_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function user_detail(){
        return $this->belongsTo(User\UserDetail::class, 'user_id', 'user_id');
    }

    public function location() : HasOne
    {
        return $this->hasOne(EventLocation::class);
    }

    public function report(){
        return $this->hasMany(EventReport::class, 'event_id', 'id')->with('report_group');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(EventReport::class, 'event_id');
    }

    public function rating(){
        return $this->hasMany(EventRating::class, 'event_id', 'id');
    }

    public function advisories(): MorphMany
    {
        return $this->morphMany(ModuleAdvisory::class, 'advisoryable');
    }

    // Filter
    protected function filterKeyword($query, $value)
    {
        return $query->with('bussiness_detail')
            ->where(function ($query) use ($value) {
                $query->where('event.event_title', 'like', "%$value%")
                ->orWhereRelation('bussiness_detail', 'fullname', 'like', "%$value%")
                ->orWhereRelation('bussiness', 'email', '=', $value)
                ->orWhereRelation('bussiness_detail', 'phone_number', '=', $value);
            });

    }

    protected function filterProvinceId($query, $value)
    {
        return $query->with('location')->WhereRelation('location', 'province_id', '=', $value);
    }

    protected function filterDistrictId($query, $value)
    {
        return $query->with('location')->WhereRelation('location', 'district_id', '=', $value);
    }

    protected function filterCreatedAt($query, $value)
    {
        $date_start =  strtotime($value['date_start'] ?? '');
        $date_end = strtotime($value['date_end'] ?? '');
        $query = $query->whereBetween($this->table . '.' . 'start_date',[$date_start,$date_end]);

        return $query;
    }

    protected function filterStartDate($query, $value)
    {
        $end_of_date = Carbon::createFromFormat('Y-m-d', $value, 'Asia/Ho_Chi_Minh')->startOfDay()->toDateTimeString();
        $date_start = strtotime($end_of_date);
        $query = $query->where($this->table . '.' . 'start_date', '>=', $date_start);
        return $query;
    }

    public function report_event(){
        return $this->hasMany(EventReport::class,'event_id','id')
            ->where(function ($query){
                $query->where(function ($q){
                    $q->where('report_result','=',1)->orWhere('report_result','=',null);
                });
            })->with('report_group');
    }
    // rating
    public function total_rating()
    {
        return $this->hasMany(EventRating::class,'event_id');
    }

    // use it only. should check then remove other has many EventRating relationships 
    public function ratings() : HasMany
    {
        return $this->hasMany(EventRating::class);
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
                'event.is_confirmed' => 1,
                'event.is_block' => 0,
                ['event.start_date', '>=', time()]
                // maybe check event.start_date >= now()->startOfDay()->timestamp
            ]);
    }

    public function scopeConfirmed($query): Builder
    {
        return $query->where([
                'event.is_confirmed' => 1,
                'event.is_block' => 0,
            ]);
    }

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighlight($query): Builder
    {
        return $query->where('is_highlight', 1);
    }

    public function canHighLight()
    {
        // het han hoac khong duyet
        return $this->status != 2 && $this->status != 3 ? true : false;
    }

    public function isHighLight()
    {
        return $this->is_highlight
            && $this->highlight_end > time();
    }

    public function getLocationAddress()
    {
        $districtName = data_get($this->location, 'district.district_name');
        $districtName = is_numeric($districtName) ? 'Quận' . $districtName : $districtName;

        return $districtName . ', ' . data_get($this->location, 'province.province_name');
    }

    public function getTitleStatus($lang = null)
    {
        if ($this->start_date >= time()) {
            return $lang == 'vn' ? 'Sắp diễn ra' : 'Coming soon...';
        } elseif (now()->endOfDay()->timestamp >= $this->start_date) {
            return 'Đang diễn ra';
        }

        return 'Đã diễn ra';
    }

    public function getImageUrl(): string
    {
        return $this->image_header_url && File::exists(public_path($this->image_header_url))
            ? asset($this->image_header_url)
            : asset('/frontend/images/event-image.png');
    }
}
