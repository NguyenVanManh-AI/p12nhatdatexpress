<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User\UserPost;
use App\Models\User;

class UserPostController extends Controller
{
    public function get_detail_post($user_code,$post_code)
    {
        $user = User::where('user_code',$user_code)->firstOrFail();
        $post = UserPost::whereHas('user')->where('is_block',0)->where('post_code',$post_code)->firstOrFail();
        return view('user.persolnal.post-detail',compact('post','user'));
    }
}
