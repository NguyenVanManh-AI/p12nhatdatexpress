<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;

class SystemHelper
{
    public static function  logo()
    {
        $logo = DB::table('system_config')->where('id', 1)->value('logo4');
        return "system/img/logo/$logo";
    }
}
