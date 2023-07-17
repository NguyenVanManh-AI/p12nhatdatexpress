<?php

namespace App\Models;

use App\Enums\UserEnum;
use App\Helpers\SystemConfig;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedComment;
use App\Models\Classified\ClassifiedReport;
use App\Models\Event\Event;
use App\Models\User\UserDetail;
use App\Models\User\UserLevel;
use App\Models\User\UserPost;
use App\Models\User\UserPostFollow;
use App\Models\User\UserPostReport;
use App\Models\User\UserLocation;
use App\Models\User\UserRating;
use App\Models\User\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User\Customer;
use App\Models\User\UserDeposit;
use App\Models\User\UserPostComment;
use App\Models\User\UserRatingComment;
use App\Models\User\UserRatingLike;
use App\Models\User\UserTransaction;
use App\Models\User\UserVoucher;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\UserHelper;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\File;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use AdminHistoryTrait;
    use UserHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // protected $dateFormat = 'U';

    public $timestamps = false;

    protected $fillable = [
        'user_code',
        'username',
        'user_level_id',
        'user_type_id',
        'coint_amount',
        'google_id',
        'facebook_id',
        'password',
        'num_block',
        'num_violate',
        'is_forbidden',
        'is_locked',
        'lock_time',
        'is_online',
        'user_ref_id',
        'is_confirmed',
        'login_method',
        'user_link_id',
        'is_link_confirm',
        'is_deleted',
        'is_highlight',
        'highlight_time',
        'rating',
        'is_active',
        'created_at',
        'updated_at',
        'email',
        'phone_number',
        'delete_time',
        'phone_securiry',
        'num_view',
        'num_view_today',
        'channel_token',
        'spammed_at'
    ];

    protected $attributes = [
        'user_level_id' => 1,
        'coint_amount' => 0,
        'num_block' => 0,
        'num_violate' => 0,
        'is_forbidden' => 0,
        'is_locked' => 0,
        'lock_time' => null,
        'is_online' => 0,
        'is_confirmed' => 0,
        'is_deleted' => 0,
        'is_highlight' => 0,
        'highlight_time' => 0,
        'rating' => 0,
        'is_active' => 0,

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'spammed_at' => 'datetime',
    ];

    public function user_detail(){
        return $this->belongsTo(UserDetail::class, 'id', 'user_id');

    }

    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    public function project_rating(){
        return $this->belongsTo(ProjectRating::class, 'id', 'user_id');

    }

    public function report_account()
    {
        return $this->hasMany(UserPostReport::class, 'personal_id')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('report_result', '=', 1)->orWhere('report_result', '=', null);
                })->where('report_position', 3);
            })->with('report_group');

    }

    public function reports(): HasMany
    {
        return $this->hasMany(UserPostReport::class, 'personal_id')
            ->where('report_position', 3);
    }

    public function user_location(){
        return $this->belongsTo(UserLocation::class, 'id', 'user_id');

    }

    public function location(): HasOne
    {
        return $this->hasOne(UserLocation::class);
    }

    public function user_type(){
        return $this->belongsTo(UserType::class,'user_type_id');

    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(UserLevel::class, 'user_level_id');
    }

    public function user_level(){
        return $this->belongsTo(UserLevel::class,'user_level_id');
    }

    public function levelStatus(): HasMany
    {
        return $this->hasMany(UserLevelStatus::class);
    }

    public function posts(){
        return $this->hasMany(UserPost::class,'user_id');

    }

    public function postComments(): HasMany
    {
        return $this->hasMany(UserPostComment::class);
    }

    public function postReports(): HasMany
    {
        return $this->hasMany(UserPostReport::class);
    }

    public function classified(){
        return $this->hasMany(Classified::class,'user_id');

    }

    public function classifieds(): HasMany
    {
        // should check deleted classified?
        return $this->hasMany(Classified::class);
    }

    public function classifiedComments(): HasMany
    {
        return $this->hasMany(ClassifiedComment::class);
    }

    public function classifiedReports(): HasMany
    {
        return $this->hasMany(ClassifiedReport::class);
    }

    public function projectComments(): HasMany
    {
        return $this->hasMany(ProjectComment::class);
    }

    public function balances(): HasMany
    {
        return $this->hasMany(UserBalance::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(UserDeposit::class);
    }

    public function coinRefReceipts(): HasMany
    {
        return $this->hasMany(UserCoinRefReceipt::class);
    }

    public function follow(){
        return $this->hasMany(UserPostFollow::class,'follow_id');

    }
    public function rating_user(){
        return $this->hasMany(UserRating::class,'rating_user_id');
    }

    // rating người dùng
    public function rangting(){
        return $this->hasMany(UserRating::class,'user_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(UserRating::class);
    }

    public function ratingUsers(): HasMany
    {
        return $this->hasMany(UserRating::class, 'rating_user_id');
    }

    public function ratingComments(): HasMany
    {
        return $this->hasMany(UserRatingComment::class);
    }

    public function ratingCommentPersonals(): HasMany
    {
    return $this->hasMany(UserRatingComment::class, 'persolnal_id');
    }

    public function ratingLikes(): HasMany
    {
        return $this->hasMany(UserRatingLike::class);
    }

    // violates
    public function violates() : HasMany
    {
        return $this->hasMany(UserViolate::class);
    }

    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'user_post_follow',
            'follow_id',
            'user_id'
        );
    }

    public function followings()
    {
        return $this->belongsToMany(
            User::class,
            'user_post_follow',
            'user_id',
            'follow_id'
        );
    }

    public function customers()
    {
        return $this->hasMany(Customer::class,'user_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function classifiedCommentLikes(): BelongsToMany
    {
        return $this->belongsToMany(
            ClassifiedComment::class,
            'classified_like_comment',
            'user_id',
            'comment_id',
        );
    }

    public function projectCommentLikes(): BelongsToMany
    {
        return $this->belongsToMany(
            ProjectComment::class,
            'project_like_comment',
            'user_id',
            'comment_id',
        );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(UserTransaction::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function projectRequests(): HasMany
    {
        return $this->hasMany(ProjectRequest::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(UserVoucher::class);
    }

    public function logContents(): HasMany
    {
        return $this->hasMany(UserLogContent::class);
    }

    public function mailConfigs(): HasMany
    {
        return $this->hasMany(UserMailConfig::class);
    }

    public function mailTemplates(): HasMany
    {
        return $this->hasMany(UserMailTemplate::class);
    }

    public function mailCampaigns(): HasMany
    {
        return $this->hasMany(UserMailCampaign::class);
    }

    public function notifies(): HasMany
    {
        return $this->hasMany(Notify::class);
    }

    public function advisories(): HasMany
    {
        return $this->hasMany(ModuleAdvisory::class);
    }

    /**
     * Attributes
     */
    public function getAvgRatingUsers()
    {
        return round($this->ratingUsers->pluck('star')->avg());
    }

    public function getFullName()
    {
        return data_get($this->detail, 'fullname');
    }

    public function getAvatarUrl()
    {
        $avatar = $this->detail ? $this->detail->avatar : '/frontend/images/personal-logo.png';

        return $avatar && File::exists(public_path($avatar))
            ? asset($avatar)
            : asset('/frontend/images/personal-logo.png');
    }

    public function getBannerLeftUrl()
    {
        $banner = data_get($this->detail, 'banner_left');

        return $banner && File::exists(public_path($banner))
            ? asset($banner)
            : null;
    }

    public function getBannerRightUrl()
    {
        $banner = data_get($this->detail, 'banner_right');

        return $banner && File::exists(public_path($banner))
            ? asset($banner)
            : null;
    }

    public function getExpertAvatar()
    {
        if ($this->detail) {
            return $this->detail->getExpertAvatar();
        }

        return $this->user_type_id == 3
            ? asset('/frontend/images/Avatar.png')
            : asset('/frontend/images/personal-logo.png');
    }

    public function getBackGroundUrl()
    {
        $background = data_get($this->detail, 'background_url');

        return $background && File::exists(public_path($background))
            ? asset($background)
            : asset(SystemConfig::USER_BACKGROUND);
    }

    public function getSeoBanner()
    {
        return $this->getBackGroundUrl();
    }

    public function getSeoTitle()
    {
        $seoTitle = data_get($this->detail, 'fullname');

        if (data_get($this->user_detail, 'intro')) {
            $seoTitle .= '-' . data_get($this->user_detail, 'intro');
        }

        return $seoTitle;
    }

    public function getSeoDescription()
    {
        return data_get($this->user_detail, 'intro');
    }

    public function getInfoStatus()
    {
        if ($this->is_deleted) {
            if ($this->isDeleted()) {
                return 'Tài khoản đã bị xóa.';
            } else {
                $deletedTime = Carbon::createFromTimestamp($this->delete_time);

                $remainingTime = $deletedTime->diffForHumans(now()->addDay(UserEnum::DELETED_DAYS * -1), [
                    'parts' => 2,
                    'syntax' => CarbonInterface::DIFF_ABSOLUTE,
                ]);

                return 'Tài khoản đang chờ xóa - Còn ' . $remainingTime;
            }
        }

        if ($this->isForbidden()) return 'Đã cấm';
        if ($this->isBlocked()) return 'Đã chặn';
        if ($this->isSpammed()) return 'Đã chặn spam';

        return $this->is_confirmed
            ? 'Hoạt động'
            : 'Chờ xác thực';
    }

    public function isActive()
    {
        if ($this->delete_time || $this->is_deleted) return false;
        if ($this->isForbidden()) return false;
        if ($this->isBlocked()) return false;

        return $this->is_confirmed
            ? true
            : false;
    }

    public function isEnterprise(): bool
    {
        return $this->user_type_id === 3 ? true : false;
    }

    public function isUserFollowing($userId): bool
    {
        return $userId && $this->followers()->where('user_id', $userId)->exists()
            ? true
            : false;
    }

    /**
     * check user is deleted after 7 days
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->is_deleted && $this->delete_time <= now()->addDays(UserEnum::DELETED_DAYS * -1)->timestamp
            ? true
            : false;
    }

    public function isBlocked()
    {
        return $this->is_locked && $this->lock_time > now()->addDays(UserEnum::BLOCK_DAYS * -1)->timestamp
            ? true
            : false;
    }

    public function isForbidden()
    {
        return $this->is_forbidden
            ? true
            : false;
    }

    public function isSpammed()
    {
        return $this->spammed_at && $this->spammed_at >= now()->addDays(UserEnum::SPAMMED_DAYS * -1);
    }

    /**
     * check user can restore
     *
     * @return boolean
     */
    public function canRestore()
    {
        return $this->is_deleted && $this->delete_time > now()->addDays(UserEnum::DELETED_DAYS * -1)->timestamp
            ? true
            : false;
    }

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query): Builder
    {
        return $query->where([
                $this->getTable() . '.is_deleted' => 0,
                $this->getTable() . '.is_forbidden' => 0,
                $this->getTable() . '.is_confirmed' => 1,
                $this->getTable() . '.is_active' => 1,
            ])
            ->where(function ($q) {
                return $q->where('is_locked', 0)
                    ->orWhereNull($this->getTable() . '.lock_time')
                    ->orWhere($this->getTable() . '.lock_time', '<', now()->addDays(UserEnum::BLOCK_DAYS * -1)->timestamp);
            });
    }
}
