<?php

namespace App\CPU;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use File;

class InitUserAccount
{
    public static function init(User $user)
    {
        #Khoi tao thu muc luu tru cua user
        $userDirPath =  "uploads/users/$user->user_code";
        if(!File::exists($userDirPath)) {
            File::makeDirectory($userDirPath);
        }

        #khoi tao goi tin mac dinh cho user
        $basePackage = DB::table('classified_package')->where('id', 1)->first();
        $userPackageData = [
            'user_id' => $user->id,
            'package_id' => $basePackage->id,
            'vip_amount' => $basePackage->vip_amount,
            'highlight_amount' => $basePackage->highlight_amount,
            'package_from_date' => time(),
            'package_to_date' => time() + $basePackage->duration_time,
            'classified_normal_amount' => $basePackage->classified_nomal_amount,
            'status' => '1',
            'created_at' => time()
        ];
        DB::table('user_balance')->insert($userPackageData);

        #Khoi tao thong in cap bac
        $levelData = [
            'user_id' => $user->id,
            'user_level' => 1,
            'amount_deposit' => 0,
            'total_classified' => 0
        ];
        DB::table('user_level_status')->insert($levelData);

    }
}
