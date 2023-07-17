<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Sysyem config data
 */
class SystemConfig
{
    #Thoi gian ton tai cua tin
    const CLASSIFIED_EXISTS_TIME = 15552000;

    #thoi gian 1 ngay quy ra giay
    const DAY_TIME = 86399;

    #So tin dang toi da trong 1 ngay
    const CLASSIFIED_PER_DAY = 3;

    #so tin dang toi da trong 1 thang
    const CLASSIFIED_PER_MONTH = 60;

    #Icon Avatar
    const REPLACE_AVATAR_IMAGE = 'user/images/icon/avatar-icon.png';

    #Banner huong dan
    const USER_GUIDE_BANNER = 'user/images/icon/huongdan.png';

    #ảnh avatar mặc định
    const USER_AVATAR = 'user/images/icon/avatar-icon.png';

    #ảnh cover mặc định
    const USER_BACKGROUND = 'user/images/icon/cover-image.png';

    /**
     * Get system's logo
     * @return string
     */
    public static function  logo()
    {
        $system = Cache::rememberForever('system_config', function() {
            return DB::table('system_config')->first();
        });

        return data_get($system, 'logo4', '/frontend/images/home/image_default_nhadat.jpg');
    }

    /**
     * Get user reference percent
     * @return int|mixed
     */
    public static function refPercent()
    {
        $refPercent =DB::table('admin_config')->where('config_code', 'C019')->value('config_value');
        return $refPercent??0;
    }

    /**
     *Get user root directory
     * @return string
     */
    public static function userDirectory()
    {
        $user = Auth::guard('user')->user();

        $userDirPath = "uploads/users/$user->user_code";

        $path = public_path($userDirPath);
        if(!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        return $userDirPath;
    }

    /**
     * User service fee
     * @param int $serviceFeeId
     * @return int
     */
    public static function serviceFee($serviceFeeId)
    {
        $feeAmount = DB::table('service_fee')->where('id', $serviceFeeId)->value('service_coin')??0;
        return $feeAmount;

    }

    public static function google_map_api (){
        $map_api = DB::table('system_config')->select('google_map')->first()->google_map ?? env('GOOGLE_API_KEY');
        return $map_api;

    }

}
