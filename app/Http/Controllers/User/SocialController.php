<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User\UserPost;

class SocialController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();
        $userFollowing = $user->followings()->withCount('followings')->paginate(5,['*'], 'page_following');
        $userTopFollow = DB::table('user as us')
            ->select('ud.user_id as id', 'ud.fullname', 'ud.image_url', 'us.user_code','upf.user_id',DB::raw("COUNT(upf.user_id) as count"))
            ->leftJoin('user_detail as ud', 'us.id', '=', 'ud.user_id')
            ->leftJoin('user_post_follow as upf', 'us.id','=', 'upf.user_id')   
            ->groupBy('upf.user_id')
            ->orderBy('count','desc')
            ->paginate(5,['*'], 'page_top_follow');
        
        $posts = UserPost::whereHas('user')->where('is_block',0);
       
        if($request->search){
            $posts->where('post_content','like','%'.$request->search.'%');
        }
        $posts = $posts->orderBy('created_at','desc')->paginate(5);
        if ($request->ajax()) {
            if($request->page){
                $html = view('user.social.item_post', compact('posts'))->render();
                return response()->json(['data'=>$html,'page'=>$posts->lastPage()],200);
            }
            if($request->page_following){
                $html = view('user.social.item_following', compact('userFollowing'))->render();
                return response()->json(['data'=>$html,'page'=>$userFollowing->lastPage()],200);
            }
            if($request->page_top_follow){
                $html = view('user.social.item_top_follow', compact('userTopFollow'))->render();
                return response()->json(['data'=>$html,'page'=>$userTopFollow->lastPage()],200);
            }
        }
        $params = [
            'posts' => $posts,
            'userFollowing' => $userFollowing,
            'userTopFollow' => $userTopFollow,
            'user' => $user,
        ];
        return view('user.social.index', $params);
    }
}
