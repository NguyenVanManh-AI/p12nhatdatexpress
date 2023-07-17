<?php

namespace App\CPU;

use App\Helpers\SystemConfig;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClassifiedService
{
    /**
     * Kiem tra so tin dang trong ngay, su dung goi tin co ban
     * @param User $user
     * @return int: 1.vuot qua quy dinh, 0. Duoc dang tin
     */
    public static function check_classified_post(User $user)
    {
        if (!$user) return 1;

        $package = (new UserService())->getCurrentBalance($user);

        // if buy package no limit for normal post
        if ($package->package_id == 1) {
            $classifiedInDay = $user->classifieds()
                ->whereBetween('created_at', [Carbon::now()->startOfDay()->timestamp, Carbon::now()->endOfDay()->timestamp])
                ->count();
            // should change if use auto check
            $normalPendingCount = (new UserService())->getClassifiedPackagePendingCount($user);

            $checkPerDay = $classifiedInDay > SystemConfig::CLASSIFIED_PER_DAY;

            if ($checkPerDay || $package->classified_normal_amount <= $normalPendingCount) {
                return 1;
            }
        }

        return 0;



        // old should remove
        $currentPackage = DB::table('user_balance')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->where('package_from_date', '<=', time())
            ->where('package_to_date', '>=', time())
            ->orderBy('package_id', 'desc')
            ->first();

        if ($currentPackage && $currentPackage->id == 1) {
            $classifiedInDay = DB::table('classified')
                ->where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->startOfDay()->timestamp)
                ->where('created_at', '<=', Carbon::now()->endOfDay()->timestamp)
                ->count('id');

            $classifiedInMonth = DB::table('classified')
                ->where('user_id', $user->id)
                ->where('created_at', '>=', $currentPackage->package_from_date)
                ->where('created_at', '<=', $currentPackage->package_from_date)
                ->count('id');

            $checkPerDay = $classifiedInDay > SystemConfig::CLASSIFIED_PER_DAY;
            $checkPerMonth = $classifiedInMonth > ($currentPackage->classified_normal_amount??SystemConfig::CLASSIFIED_PER_MONTH);
            if ($checkPerDay && $checkPerMonth) {
                return 1;
            }
        }

        return 0;
    }
}
