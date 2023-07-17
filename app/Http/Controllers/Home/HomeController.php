<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Auth\RegisterController;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = \App\Models\User::latest()->first();
        (new RegisterController())->sendActiveMail($user);

        $one_day=60*60*24;
        $one_week=$one_day *7;
        $ten_day=$one_day *10;
        $one_month=$one_day *30;
        $array_time = [$one_day,$one_week,$ten_day,$one_month];
        $popup_home = DB::table('home_config')->select('popup_time_expire','popup_time','popup_status','popup_image','popup_link','header_text_block','desktop_header_image','meta_title','meta_key','meta_desc')->first();
        $time_popup = $array_time[$popup_home->popup_time];

        return view('Home.Index.Index',compact('popup_home','time_popup'));
    }
}
