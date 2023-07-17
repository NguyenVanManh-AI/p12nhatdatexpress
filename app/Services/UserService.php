<?php

namespace App\Services;

use App\Enums\NotifyStatus;
use App\Enums\UserViolateStatus;
use App\Helpers\Helper;
use App\Helpers\SystemConfig;
use App\Models\ClassifiedPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Enums\UserEnum;

class UserService
{
    /**
     * Get all users
     * @param array $queries
     *
     * @return Collection|null $users
     */
    public function index(array $queries = [])
    {
        $itemsPerPage = data_get($queries, 'items') ?: 10;
        $page = data_get($queries, 'page') ?: 1;
        $users = User::with('user_level', 'user_type', 'user_location', 'user_detail')
            ->join('user_type', 'user.user_type_id', '=', 'user_type.id')
            ->leftJoin('user_location', 'user.id', '=', 'user_location.user_id')
            ->leftJoin('user_detail', 'user.id', '=', 'user_detail.user_id')
            ->leftJoin('province', 'province.id', '=', 'user_location.province_id')
            ->leftJoin('district', 'district.id', '=', 'user_location.district_id')
            ->leftJoin('user_balance', 'user_balance.user_id', '=', 'user.id')
            ->leftJoin('classified_package', 'user_balance.package_id', '=', 'classified_package.id')
            ->select('user.*', 'province.province_name as province', 'district.district_name as district', 'classified_package.package_name', 'user_detail.image_url as user_image')
            ->when(data_get($queries, 'not_type_id'), function ($query, $notTypeId) {
                return $query->where('user.user_type_id', '!=', $notTypeId);
            })
            ->when(data_get($queries, 'type_id'), function ($query, $typeId) {
                return $query->where('user.user_type_id', $typeId);
            })
            ->when(data_get($queries, 'created_at'), function ($query, $createdAt) {
                return $query->whereBetween('user.created_at', [strtotime(Carbon::parse($createdAt)), strtotime(Carbon::parse($createdAt)->addDay())]);
            })
            ->when(data_get($queries, 'province'), function ($query, $provinceId) {
                return $query->where('user_location.province_id', $provinceId);
            })
            ->when(data_get($queries, 'district'), function ($query, $districtId) {
                return $query->where('user_location.district_id', $districtId);
            })
            ->when(data_get($queries, 'level'), function ($query, $levelId) {
                return $query->where('user.user_level_id', $levelId);
            })
            ->when(data_get($queries, 'status'), function ($query, $status) {
                return $query->where('user.is_confirmed', $status);
            })
            ->when(data_get($queries, 'user_type'), function ($query, $userTypeID) {
                return $query->where('user.user_type_id', $userTypeID);
            })
            ->when(data_get($queries, 'keyword'), function ($query, $keyWord) {
                $query->where(function ($query) use ($keyWord) {
                    return $query->where('user_detail.fullname', 'LIKE', '%' . $keyWord . '%')
                        ->orWhere('user.email', 'LIKE', '%' . $keyWord . '%')
                        ->orWhere('user.phone_number', 'LIKE', '%' . $keyWord . '%');
                });
            });

        # Filter with state (Trạng thái của user (Chờ xác thực, đã xóa, đã cấm, đã chặn, hoạt động, ...))
        if (is_numeric(data_get($queries, 'state'))) {
            $this->sortState($users, $queries);
        }

        $users = $users->latest()
            ->groupBy('user.id')
            ->distinct()
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $users;
    }

    public function sortState($query, $queries)
    {
        # Lọc với tình trạng (state) => [
        # 0 => Chờ xác thực
        # 1 => Hoạt động
        # 2 => Đã chặn
        # 3 => Đã cấm
        # 4 => Đã xóa
        #  ]
        switch (data_get($queries, 'state')) {
            case 0:
                $query = $query
                    ->where('is_confirmed', 0)
                    ->where('user.is_deleted', 0)
                    ->where('is_forbidden', 0)
                    ->where('is_locked', 0);;
                break;
            case 1:
                $query = $query
                    ->where('is_confirmed', 1)
                    ->where('user.is_deleted', 0)
                    ->where('is_forbidden', 0)
                    ->where('is_locked', 0);
                break;
            case 2:
                $query = $query->where('is_locked', 1)->where('lock_time', '>', time());
                break;
            case 3:
                $query = $query->where('is_forbidden', 1);
                break;
            case 4:
                $query = $query->where('user.is_deleted', 1);
                break;
            default:
                break;
        }
    }

    /**
     * update user details
     * @param User $user
     * @param array $data
     *
     * @return User $user
     */
    public function update(User $user, array $data)
    {
        $userData = [
            'email' => data_get($data, 'email'),
            'phone_number' => data_get($data, 'phone_number'),
            'phone_securiry' => data_get($data, 'phone_securiry') ? true : false,
        ];
        $user->update($userData);

        $this->updateUserDetails($user, $data);
        $this->updateUserLocation($user, $data);

        create_user_log(1, '');

        return $user;
    }

    /**
     * update user detail
     * @param User $user
     * @param array $data
     *
     * @return void
     */
    public function updateUserDetails(User $user, array $data)
    {
        $detailData = [
            'fullname' => data_get($data, 'fullname'),
            'birthday' => strtotime(data_get($data, 'birthday')),
            'website' => data_get($data, 'website'),
            'intro' => data_get($data, 'intro'),
            'tax_number' => data_get($user->detail, 'tax_number', data_get($data, 'tax_number')),
        ];

        $user->detail()->updateOrCreate([], $detailData);
    }

    /**
     * update user location
     * @param User $user
     * @param array $data
     *
     * @return void
     */
    public function updateUserLocation(User $user, array $data)
    {
        $locationData = [
            'province_id' => data_get($data, 'province_id'),
            'district_id' => data_get($data, 'district_id'),
            'ward_id' => data_get($data, 'ward_id'),
        ];

        $user->location()->updateOrCreate([], $locationData);
    }

    /**
     * create user violate
     * @param User $user
     * @param array $data
     *
     * @return void
     */
    public function createViolate(User $user, $data) : void
    {
        Helper::createUserViolate($data);

        // create user log
        if (data_get($data, 'log_id')) {
            create_user_log(data_get($data, 'log_id'), data_get($data, 'log_message'));
        }
    }

    /**
     * Update user avatar
     * @param User $user
     * @param File $avatar
     *
     * @return void
     */
    public function updateAvatar(User $user, $avatar): void
    {
        $userDetail = $user->detail()->firstOrCreate();

        $currentAvatar = $userDetail->image_url;
        if ($currentAvatar) {
            File::delete($currentAvatar);
        }

        $path = upload_image($avatar, SystemConfig::userDirectory());
        $userDetail->update([
            'image_url' => $path
        ]);

        createUserLog('user-update-avatar');
    }

    /**
     * Update user password
     * @param $user $user
     * @param string|null $password
     *
     * @return void
     */
    public function updatePassword(User $user, $password)
    {
        if (!$password) return
        $user->update([
            'password' => bcrypt($password)
        ]);
        // Helper::create_admin_log(158, $data);
        // maybe need send mail
    }

    /**
     * create user violate
     * @param User $user
     * @param string $action
     *
     * @return void
     */
    public function checkBanUser(User $user, string $action)
    {
        if (!$action) return;

        // check ban user
        $userViolationCounts = $user->violates()
            ->where('status', UserViolateStatus::ACTIVE)
            // ->where('action', $action)
            ->count();

        // 3 times lock more than 3 forbidden
        if ($userViolationCounts == UserEnum::NUMBER_BLOCK) {
            // block log
            $this->blockUser($user);
        } elseif ($userViolationCounts > UserEnum::NUMBER_BLOCK && !$user->is_forbidden) {
            // forbidden log
            $this->forbiddenUser($user);
        }

        // should remove
        $user->increment('num_violate');
    }

    /**
     * block user
     * @param User $user
     * @param $message = null
     *
     * @return User $user
     */
    public function blockUser(User $user, $message = null)
    {
        if ($user->isBlocked()) return;
        // create_user_log(16); // Tài khoản bị chặn
        $user->update([
            'is_locked' => true,
            'lock_time' => time(),
            'num_block' => $user->num_block + 1
        ]);

        $notifyData = [
            'icon' => 'fas fa-lock text-danger',
            'content' => $message ?: 'Tài khoản đã bị khóa ' . UserEnum::BLOCK_DAYS . ' ngày'
        ];
        $this->createNotification($user, $notifyData);
        // Helper::create_admin_log(39, [
        //     'is_locked' => 1,
        //     'lock_time' => time(),
        //     'num_block' => $user->num_block
        // ]);
        // admin log_type = R00x = báo cáo. log_type = Q00x = ?,
        // block 39/159, unblock 40/160,
        // forbidden: 41/161, unforbidden: 42/162
        // delete: 43/163, restore: 44/164

        if ($user->num_block >= UserEnum::NUMBER_BLOCK && !$user->is_forbidden) {
            $this->forbiddenUser($user);
        }

        return $user;
    }

    /**
     * unlock user
     * @param User $user
     * @param $message = null
     *
     * @return User $user
     */
    public function unblockUser(User $user, $message = null)
    {
        if (!$user->isBlocked()) return;

        $user->update([
            'is_locked' => false,
            'lock_time' => null
        ]);

        $notifyData = [
            'icon' => 'fas fa-unlock text-success',
            'content' => $message ?: 'Tài khoản đã được bỏ khóa'
        ];
        $this->createNotification($user, $notifyData);
        // Helper::create_admin_log(40, [
        //     'is_locked' => 0,
        //     'lock_time' => null,
        // ]);

        return $user;
    }


    /**
     * forbidden user
     * @param User $user
     * @param $message = null
     *
     * @return User $user
     */
    public function forbiddenUser(User $user, $message = null)
    {
        if ($user->isForbidden()) return;

        // create_user_log(15); // Tài khoản bị cấm
        $user->update([
            'is_forbidden' => true,
        ]);

        $notifyData = [
            'icon' => 'fas fa-ban text-danger',
            'content' => $message ?: 'Tài khoản đã bị cấm'
        ];
        $this->createNotification($user, $notifyData);
        // Helper::create_admin_log(41, [
        //     'is_forbidden' => 1
        // ]);

        return $user;
    }

    /**
     * unforbidden user
     * @param User $user
     * @param $message = null
     *
     * @return User $user
     */
    public function unforbiddenUser(User $user, $message = null)
    {
        if (!$user->isForbidden()) return;

        $user->update([
            'is_forbidden' => false,
            'num_block' => 0
        ]);

        $notifyData = [
            'icon' => 'fas fa-check text-success',
            'content' => $message ?: 'Tài khoản đã được bỏ cấm'
        ];
        $this->createNotification($user, $notifyData);
        // Helper::create_admin_log(42, [
        //     'is_forbidden' => 0
        // ]);

        return $user;
    }

    /**
     * spam user
     * @param User $user
     * @param $message = null
     *
     * @return User $user
     */
    public function spammedUser(User $user, $message = null)
    {
        $user->update([
            'spammed_at' => now()
        ]);

        $notifyData = [
            'icon' => 'fas fa-comment-slash text-warning',
            'content' => $message ?: 'Tài khoản đã bị chặn spam ' . UserEnum::SPAMMED_DAYS . ' ngày'
        ];

        $this->createNotification($user, $notifyData);

        return $user;
    }

    /**
     * un spam user
     * @param User $user
     * @param $message = null
     *
     * @return User $user
     */
    public function unSpammedUser(User $user, $message = null)
    {
        $user->update([
            'spammed_at' => null
        ]);

        $notifyData = [
            'icon' => 'fas fa-comments text-success',
            'content' => $message ?: 'Tài khoản đã được mở chặn spam'
        ];

        $this->createNotification($user, $notifyData);

        return $user;
    }

    /**
     * delete user
     * @param User $user
     * @param $time = null
     *
     * @return User $user
     */
    public function deleteUser(User $user, $time = null)
    {
        if ($user->is_deleted && $user->delete_time) return;

        $user->update([
            'is_deleted' => true,
            'delete_time' => $time ?: time()
        ]);

        $notifyData = [
            'icon' => 'fas fa-user-times text-danger',
            'content' => 'Tài khoản đã bị xóa'
        ];
        $this->createNotification($user, $notifyData);
        // Helper::create_admin_log(43, [
        //     'is_deleted'=>1
        // ]);

        return $user;
    }

    /**
     * restore user
     * @param User $user
     *
     * @return User $user
     */
    public function restoreUser(User $user)
    {
        if (!$user->is_deleted && !$user->delete_time) return;

        $user->update([
            'is_deleted' => false,
            'delete_time' => null
        ]);

        $notifyData = [
            'icon' => 'fas fa-user-check text-success',
            'content' => 'Tài khoản đã được khôi phục'
        ];
        $this->createNotification($user, $notifyData);
        // Helper::create_admin_log(44, [
        //     'is_deleted' => 0
        // ]);

        return $user;
    }

    /**
     * create notification
     * @param User $user
     * @param array $data
     *
     * @return $notification
     */
    public function createNotification(User $user, array $data)
    {
        $user->notifies()->create([
            'icon' => data_get($data, 'icon'),
            'content' => data_get($data, 'content'),
            'status' => NotifyStatus::PUBLISHED,
            'read' => false,
        ]);
    }

    /**
     * get/create user balance | default package
     * @param User $user
     *
     * @return $userBalance
     */
    public function getCurrentBalance(User $user)
    {
        $currentPackage = $user->balances()
            ->select('user_balance.*')
            ->join('classified_package', 'classified_package.id', '=', 'user_balance.package_id')
            ->where('user_balance.package_from_date', '<=', time())
            ->where('user_balance.package_to_date', '>=', time())
            ->where('user_balance.status', 1)
            ->where('classified_package.is_deleted', 0)
            ->orderBy('user_balance.package_id', 'desc')
            ->first();

        if (!$currentPackage) {
            $basePackage = ClassifiedPackage::find(1);

            $currentPackage = $user->balances()
                ->firstOrCreate([
                    'package_id' => $basePackage->id,
                    'status' => 1,
                ], [
                    'vip_amount' => data_get($basePackage, 'vip_amount', 20),
                    'highlight_amount' => data_get($basePackage, 'highlight_amount', 0),
                    // default package do not need time
                    // 'package_from_date' => time(),
                    // 'package_to_date' => time() + data_get($basePackage, 'duration_time', 2592000),
                    'classified_normal_amount' => data_get($basePackage, 'classified_nomal_amount', SystemConfig::CLASSIFIED_PER_MONTH),
                    'created_at' => time()
                ]);
        }

        return $currentPackage;
    }

    /**
     * get pending classified count for current package
     * @param User $user
     * @param string $type normal|vip|highlight
     *
     * @return int $pendingCount
     */
    public function getClassifiedPackagePendingCount(User $user, $type = 'normal')
    {
        $currentPackage = $this->getCurrentBalance($user);

        switch ($type) {
            case 'normal':
                $pendingCount = $user->classifieds()
                    ->whereNull('is_hightlight')
                    ->whereNull('is_vip')
                    ->where('expired_date', '>', time())
                    ->where('confirmed_status', 0) // pending status
                    ->where('user_balance_id', $currentPackage->id)
                    ->count();
                break;
            case 'vip':
                $pendingCount = $user->classifieds()
                    // ->leftJoin('user_transaction', function ($join) {
                    //     $join->on('user_transaction.classified_id', '=', 'classified.id')
                    //         ->where('user_transaction.transaction_type', 'S')
                    //         ->where('user_transaction.sevice_fee_id', 2);
                    // })
                    // ->whereNull('user_transaction.id') // ko mua bang coin
                    ->where('is_vip', 1)
                    ->where('vip_begin', '<=', time())
                    ->where('vip_end', '>', time())
                    ->where('expired_date', '>', time())
                    ->where('confirmed_status', 0) // pending status
                    ->where('user_balance_id', $currentPackage->id)
                    ->count();
                break;
            case 'highlight':
                $pendingCount = $user->classifieds()
                    ->where('is_hightlight', 1)
                    ->where('hightlight_begin', '<=', time())
                    ->where('hightlight_end', '>', time())
                    ->where('expired_date', '>', time())
                    ->where('confirmed_status', 0) // pending status
                    ->where('user_balance_id', $currentPackage->id)
                    ->count();
                break;
            default:
                $pendingCount = 0;
                break;
        }

        return $pendingCount;
    }

    /**
     * Sync user deploying projects
     * @param User $user
     * @param array $projectIds
     *
     * @return void
     */
    public function syncProjects(User $user, array $projectIds)
    {
        $user->projects()->sync($projectIds);
        createUserLog('user-deploying-projects');
    }

    /**
     * Create project request
     * @param User $user
     * @param array $data
     *
     * @return Project $project
     */
    public function createProjectRequest(User $user, array $data)
    {
        $projectName = data_get($data, 'project_name');

        $user->projectRequests()->create([
            'project_name' => $projectName,
            'investor' => data_get($data, 'investor'),
            'address' => data_get($data, 'address'),
            'province_id' => data_get($data, 'province_id'),
            'district_id' => data_get($data, 'district_id'),
            'ward_id' => data_get($data, 'ward_id'),
            'created_at' => time(),
            'user_id' => $user->id
        ]);

        // create log
        createUserLog('user-add-project-request', $projectName);
    }
}

