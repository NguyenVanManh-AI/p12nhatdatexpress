<?php

namespace App\Http\Controllers\User;

use App\Enums\UserViolateAction;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Personal\PostCommentRequest;
use App\Http\Requests\Personal\ReportRequest;
use App\Http\Requests\User\Persolnal\AddPostRequest;
use App\Http\Requests\User\Persolnal\UserPostReportRequest;
use App\Http\Requests\User\Personal\AddCommentRequest;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedParam;
use App\Models\District;
use App\Models\Group;
use App\Models\Province;
use App\Models\User;
use App\Models\User\UserRating;
use App\Models\User\UserPost;
use App\Models\User\UserPostComment;
use App\Models\User\UserPostReport;
use App\Services\PersonalPageService;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PersolnalPageController extends Controller
{
    private UserService $userService;
    private PersonalPageService $personalPageService;

    public function __construct()
    {
        $this->userService = new UserService;
        $this->personalPageService = new PersonalPageService;
    }

    # sort classified
    private function sort_classified($request, $query)
    {
        if (isset($request->sort)) {
            if ($request->sort == 'luot-xem-nhieu-nhat') {
                $query = $query->orderBy('classified.num_view', 'desc');
            }
            if ($request->sort == 'gia-cao-nhat') {
                $query = $query->orderBy('price_classified', 'desc');
            }
            if ($request->sort == 'gia-thap-nhat') {
                $query = $query->orderBy('price_classified', 'asc');
            }
            if ($request->sort == 'dien-tich-lon-nhat') {
                $query = $query->orderBy('classified.classified_area', 'desc');
            }
            if ($request->sort == 'dien-tich-nho-nhat') {
                $query = $query->orderBy('classified.classified_area', 'asc');
            }
        }
        return $query;
    }
    // danh sách bài viết
    public function get_list($user_code)
    {
        $auth = auth()->guard('user');
        $user = User::where('user_code', $user_code)
            ->firstOrFail();

        if (!$user->isActive() && $user->id != $auth->id()) {
            return abort(404);
        }

        $post = UserPost::with('comment.user.user_detail','comment.like','comment.child')
            ->where('user_id',$user->id)->where('is_block',0)->where('is_show',1)->orderBy('created_at','desc')->get();

        if(!Cache::get($user->id.'check_num_view')){
            $user->num_view += 1;
            $user->num_view_today += 1;
            $user->save();
        }
        Cache::remember($user->id.'check_num_view', 300,function () {
            return true;
        });

        return view('user.persolnal.index',compact('auth','post','user'));

    }
    # đăng bài viết
    public function post_posts(AddPostRequest $request){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }

        # check block/forbidden for post
        $blockMessage = user_blocked('đăng bài');
        if ($blockMessage) {
            Toastr::error($blockMessage);
            return back();
        }

        $user = Auth::guard('user')->user();

        // check forbidden words then return if have or maybe just save and hide it
        $checkFields = [
            'content_post'
        ];

        $forbiddenWords = [];

        foreach ($checkFields as $field) {
            $checkForbiddenWords = (array) Helper::checkBlockedKeyword(data_get($request, $field), 1);
            $forbiddenWords = array_unique(array_merge($forbiddenWords, $checkForbiddenWords));
        }

        if ($forbiddenWords) {
            $action = UserViolateAction::PERSONAL_POST_FORBIDDEN_WORD;
            $violateData = [
                'user_id' => $user->id,
                // 'target_id' => $id, // if save & hide
                // 'target_type' => UserPost::class, // if save & hide
                'action' => $action,
                'options' => [
                    'forbidden_words' => $forbiddenWords
                ],
                // log data
                'log_id' => 17, // Bình luận chứa từ cấm
                'log_message' => null,
            ];
            $this->userService->createViolate($user, $violateData);
            $this->userService->checkBanUser($user, $action);

            Toastr::error('Bình luận có chứa từ cấm: ' . implode(', ', $forbiddenWords));
            return back();
        }
        // end check forbidden words

        $post = new User\UserPost();
        $post->user_id = Auth::guard('user')->id();
        $post->post_content = $request->content_post;
        $arr = [];
        if($request->hasFile('image')){
            $allow = ['image/jpeg','image/gif','image/png'];
            foreach ($request->file('image') as $i){
                if (!in_array($i->getMimeType(),$allow)){
//                    dd('dsa');
                    return back()->with('error_img','Chỉ được phép tải lên hình ảnh');
                }
            }

            foreach ($request->file('image') as $key => $i){
                $name =$key.time().Str::random(4).'.'.$i->getClientOriginalExtension();
                $i->move(public_path('uploads/posts/'),$name);
                array_push($arr,'uploads/posts/'.$name);
            }
            $post->post_image = json_encode($arr);
        }
        $post->created_at = time();
        $post->is_show = 1;
        $post->created_by = Auth::guard('user')->id();
        do
            $ran = rand(1000,9999999999);
        while (User\UserPost::where('post_code',$ran)->first());
        $post->post_code = $ran;
        $post->save();
        Toastr::success('Thêm thành công');
        return back();
    }
    # like - unlike bài viết
    public function like_post($id){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }
        $value = '';
        $item = User\UserPostLike::where('user_id',Auth::guard('user')->id())->where('post_id',$id)->first();
        if($item== null){
            $value = 'like';
            DB::table('user_post_like')->insert([
                'user_id'=>Auth::guard('user')->id(),
                'post_id'=>$id
            ]);
            return response()->json($value,200);
        }
        $value = 'unlike';
        DB::table('user_post_like')->where('user_id',Auth::guard('user')->id())->where('post_id',$id)->delete();
        return response()->json($value,200);
    }
    # like - unlike bình luận
    public function like_comment($id){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }
        $value = '';
        $item = User\UserPostLikeComment::where('user_id',Auth::guard('user')->id())
            ->where('comment_id',$id)->first();
        if($item == null){
            $value = 'like';
            DB::table('user_post_like_comment')->insert([
                'user_id'=>Auth::guard('user')->id(),
                'comment_id'=>$id
            ]);
            return response()->json($value,200);
        }
        $value = 'unlike';
        $item = User\UserPostLikeComment::where('user_id',Auth::guard('user')->id())
            ->where('comment_id',$id)->delete();
        return response()->json($value,200);
    }
    #follow - unfollow
    public function follow_user($id){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }
        $item = User\UserPostFollow::where([
            'user_id'=>Auth::guard('user')->id(),
            'follow_id'=>$id
        ])->first();
        if(!Auth::guard('user')->check()){
            return response()->json(['error'=>'Vui lòng đăng nhập'],200);
        }
        if($item == null){
            DB::table('user_post_follow')->insert([
                'user_id'=>Auth::guard('user')->id(),
                'follow_id'=>$id
            ]);
            return response()->json(['success'=>'Đã follow','status'=>1],200);
        }
        else{DB::table('user_post_follow')->where([
            'user_id'=>Auth::guard('user')->id(),
            'follow_id'=>$id
        ])->delete();
            return response()->json(['success'=>'Đã bỏ follow','status'=>0],200);}
    }
    // danh sách tin đăng
    public function list_classified(Request $request,$user_code ){
        $auth = auth()->guard('user');
        $user = User::where('user_code', $user_code)
            ->firstOrFail();
        if (!$user->isActive() && $user->id != $auth->id()) {
            return abort(404);
        }

        $params['user'] = $user;

        $classified =Classified::query()
            ->leftJoin('group','classified.group_id','=','group.id')
            ->leftJoin('group as group_parent','group.parent_id','=','group_parent.id')
            ->leftJoin('group as group_parent_parent','group_parent.parent_id','=','group_parent_parent.id')
            ->leftJoin('classified_location','classified_location.classified_id','=','classified.id')
            ->select('classified.*',
                'group_parent.group_url as group_parent_url',
                'group_parent.id as group_parent_id',
                'group_parent_parent.group_url as group_parent_parent_url',
                'group_parent_parent.id as group_parent_parent_id',
                DB::raw('(CASE WHEN classified.price_unit_id = 2 THEN classified.classified_price *1000 WHEN classified.price_unit_id = 4  THEN classified.classified_price*1000 WHEN classified.price_unit_id = 6  THEN classified.classified_price/10 ELSE classified.classified_price END) as price_classified'),
                DB::raw("(CASE WHEN (classified.is_hightlight = 1 AND classified.hightlight_end >".time().") THEN 1 WHEN (classified.is_vip = 1 AND classified.vip_end >".time().") THEN 2  ELSE 3 END ) as vip"),
            )
            ->where('classified.user_id',$params['user']->id)
            ->showed()
            ->oldest('vip')
            ->latest('classified.renew_at');

        $classified = $this->sort_classified($request, $classified);
        $provinces = Province::all();
        $districts = District::all();
        $groups = Group::all();
        $rooms=ClassifiedParam::where('param_type','=','B')->get();
        $request->keyword?$classified->where('classified.classified_name','LIKE','%'.$request->keyword.'%'):null;
        $request->province_id !=''?$classified->where('classified_location.province_id','=',$request->province_id):null;
        $request->districts_id !=''?$classified->where('classified_location.district_id','=',$request->district_id):null;
        $request->group_id !=''?$groups->where('classified.group_id','=',$request->group_id):null;
        $request->num_bed !=''?$rooms->where('classified.num_bed','=',$request->num_bed):null;
        if($request->has('sort') && $request->sort!=""){
            $request->sort == 'gia-cao-nhat'?null:null;
        }
        if($request->priceBillion) {
            $price = explode('-',$request->priceBillion);
            if($price[0]!=0 && $price[1] !=0){
                $classified = $classified->havingRaw('price_classified >='.$price[0]*1000);
                $classified = $classified->havingRaw('price_classified <='.$price[1]*1000);
            }
            elseif($price[0]==0){
                $classified = $classified->havingRaw('price_classified <='.$price[1]*1000);
            }
            else {
                $classified = $classified->havingRaw('price_classified >='.$price[0]*1000);
            }
        }
        $classified=$classified->paginate(10);
        return view('user.persolnal.list',compact('params','classified','provinces','districts','groups','rooms'));
    }
    # add - update banner left right
    public function upload_banner(Request $request){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }

        # check block/forbidden
        $blockMessage = user_blocked('thay banner');
        if ($blockMessage) return response()->json($blockMessage, 403);

        $user = Auth::guard('user')->user();
        $user_detail = $user->detail()->firstOrCreate();

        // điều kiện uploads banner
        $check_banner = DB::table('admin_config')->where('config_code','C013')->pluck('config_value')->first();
        $level_name = DB::table('user_level')->where('id',(int)$check_banner??3)->pluck('level_name')->first();
        if($user_detail->user->user_level_id <(int)$check_banner??3){
            return response()->json('Cấp bậc '.$level_name??'Bạc'.' mới được thay banner',401);
        }
        if($request->hasFile('banner_left')){
            if($user_detail->banner_left!='' && file_exists(public_path($user_detail->banner_left))){
                unlink(public_path($user_detail->banner_left));
            }
            $name =Str::random(4).time().'.'. $request->file('banner_left')->getClientOriginalExtension();
            $request->file('banner_left')->move(public_path('uploads/banner/'),$name);
            $user_detail->banner_left = 'uploads/banner/'.$name;
        }
        if($request->hasFile('banner_right')){
            if($user_detail->banner_right!='' && file_exists(public_path($user_detail->banner_right))){
                unlink(public_path($user_detail->banner_right));
            }
            $name =Str::random(4).time().'.'. $request->file('banner_right')->getClientOriginalExtension();
            $request->file('banner_right')->move(public_path('uploads/banner/'),$name);
            $user_detail->banner_right = 'uploads/banner/'.$name;
        }
        $user_detail->save();
        return response()->json('cập nhật thành công',200);
    }
    # update avatar
    public function upload_avatar(Request $request)
    {
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }

        # check block/forbidden for comment
        $blockMessage = user_blocked('thay avatar');
        if ($blockMessage) return response()->json($blockMessage, 403);

        if ($request->hasFile('avatar')) {
            $user = Auth::guard('user')->user();
            $this->userService->updateAvatar($user, $request->avatar);
        }

        return response()->json('cập nhật thành công', 200);
    }

    # add comment
    public function add_comment ($id, AddCommentRequest $request){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }

        # check block/forbidden for comment
        $blockMessage = user_blocked('bình luận');
        if ($blockMessage) {
            return response()->json($blockMessage, 403);
        }

        $user = Auth::guard('user')->user();

        $comment = new User\UserPostComment();
        if($request->has('comment_id') && $request->comment_id!=null){
            $comment->parent_id = $request->comment_id;
        }
        $user = Auth::guard('user')->user();
        $comment->user_id = $user->id;
        $comment->user_post_id =$id;
        $comment->comment_content=$request->content_comment;
        $comment->is_show=1;
        $comment->created_at=time();
        $comment->save();
        return response()->json([
            'success' => true,
            'data' => [
                'user_code'=>$user->user_code,
                'fullname'=>$user->user_detail->fullname,
                'avatar'=>$user->user_detail->image_url,
                'comment'=>$comment,
                'date'=>Helper::get_time_to($comment->created_at)
            ]
        ], 200);
    }
    # đánh giá
    public function evaluate(Request $request,$user_code){

        $params = [
            'user'=>User::where('user_code',$user_code)->first(),
        ];
        if($params['user'] == null){
            Toastr::error('Không tồn tại người dùng');
            return back();
        }
        // get tất cả các comment
        $comment = User\UserRatingComment::query()
            ->where('persolnal_id',$params['user']->id)
            ->orderByDesc('created_at')
            ->get();

        $rating = UserRating::where('rating_user_id',$params['user']->id)->get();
        $rating['1'] = (int)round(UserRating::where('rating_user_id',$params['user']->id)->where('rating_type',1)->avg('star'));
        $rating['2'] = (int)round(UserRating::where('rating_user_id',$params['user']->id)->where('rating_type',2)->avg('star'));
        $rating['3'] = (int)round(UserRating::where('rating_user_id',$params['user']->id)->where('rating_type',3)->avg('star'));
        $rating['total'] = (int)round(collect([ $rating['1'], $rating['2'], $rating['3']])->avg());
        $user = User::find(Auth::guard('user')->id()??-1);
        return view('user.persolnal.evaluate',compact('params','rating','comment','user'));
    }
    // thêm đánh giá
    public function evaluate_post(Request $request,$user_code){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }
        $params = [
            'user'=>User::where('user_code',$user_code)->first(),
        ];
        $user_id = Auth::guard('user')->id();
        #khả năng tư vấn
        $advise1 = new UserRating();
        $advise2= new UserRating();
        $advise3 = new UserRating();
        $advise1->user_id = $user_id;
        $advise2->user_id = $user_id;
        $advise3->user_id = $user_id;
        $advise1->star = $request->rate??5;
        $advise2->star = $request->rate_1??5;
        $advise3->star = $request->rate_2??5;
        $advise1->created_at = time();
        $advise2->created_at = time();
        $advise3->created_at = time();
        $advise1->rating_type =1;
        $advise2->rating_type =2;
        $advise3->rating_type =3;
        $advise1->rating_user_id =$params['user']->id;
        $advise2->rating_user_id =$params['user']->id;
        $advise3->rating_user_id =$params['user']->id;
        $advise1->save();
        $advise2->save();
        $advise3->save();
        $rangtins = UserRating::query()
            ->where('rating_user_id',$params['user']->id)->pluck('star')->avg();
        $params['user']->rating = $rangtins;
        $params['user']->save();
        Toastr::success('Đánh giá thành công');
        return back();

    }
    # Thêm bình luận
    public function post_comment_rating(Request  $request,$user_code){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }
        $params = [
            'user'=>User::where('user_code',$user_code)->first(),
        ];

        if($params['user'] == null){
            return response()->json('Không tồn tại người dùng',404);
        }
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }
        $comment_ratin = new User\UserRatingComment();
        $comment_ratin->user_id = Auth::guard('user')->id();
        $comment_ratin->comment_content = $request->content_comment;
        $comment_ratin->persolnal_id = $params['user']->id;
        if($request->has('comment_id')){
            $comment_ratin->parent_id = $request->comment_id;
        }
        $comment_ratin->created_at = time();
        $user = User::find(Auth::guard('user')->id());

        // kiểm tra đánh giá
        $rating = UserRating::where(['user_id'=>$user->id,'rating_user_id'=>$params['user']->id])->pluck('star')->toArray();
        if(count($rating) == 0){
            $value = 0;
            $star = -1;
        }
        else {
            $value = 1;
            $star= (int)round(array_sum($rating) / count($rating));
        }
        if($user->id == $params['user']->id){
            $value =2;
        }
        $comment_ratin->save();
        return response()->json(['id'=>$comment_ratin->id,'avatar'=>$user->user_detail->image_url,'fullname'=>$user->user_detail->fullname,'created_at'=>date('d/m/Y',time()),'check_rating'=>$value,'star'=>$star],200);
    }
    # like - unlike bình luận đánh giá
    public function like_rating($id){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',403);
        }
        $like = User\UserRatingLike::where(['user_id'=>Auth::guard('user')->id(),'comment_id'=>$id])->first();
        if($like == null){
            $value = 'like';
            DB::table('user_rating_like')->insert([
                'user_id'=>Auth::guard('user')->id(),
                'comment_id'=>$id,
            ]);
            return response()->json($value,200);

        }
        else{
            $value = 'unlike';
            $like->delete();
            return response()->json($value,200);
        }
    }

    // báo cáo trang cá nhân
    public function  report_persolnal(ReportRequest $request, $id)
    {
        // kiểm tra đăng nhập
        if(!Auth::guard('user')->check()){
            Toastr::error('Vui lòng đăng nhập');
            return back();
        }

        $user = Auth::guard('user')->user();
        $personal = User::findOrFail($id);

        $reported = $personal->reports()
            ->firstWhere('user_id', $user->id);
        if($reported) {
            Toastr::error('Mỗi tài khoản chỉ được báo cáo 1 lần');
            return back();
        }

        $request['user_id'] = $user->id;
        $request['report_position'] = 3;
        $report = $this->personalPageService->createReport($personal, $request->all());

        // check
        return $report->user->isBlocked()
            ? redirect(route('home.index'))
            : back();
    }
    // báo cáo nội dung
    public function  report_content(UserPostReportRequest $request, $post_id)
    {
        // kiểm tra đăng nhập
        if(!Auth::guard('user')->check()){
            Toastr::error('Vui lòng đăng nhập');
            return back();
        }

        $user = Auth::guard('user')->user();
        $post = UserPost::findOrFail($post_id);

        $reported = $post->reports()
            ->firstWhere('user_id', $user->id);
        if($reported) {
            Toastr::error('Mỗi tài khoản chỉ được báo cáo 1 lần');
            return back();
        }

        $request['user_id'] = $user->id;
        $request['report_position'] = 1;
        $report = $this->personalPageService->createReport($post, $request->all());

        Toastr::success('Báo cáo thành công');
        return $report->userPost->is_block
            ? redirect(route('home.index'))
            : back();
    }

    // báo cáo bình luận
    public function  report_comment(PostCommentRequest $request, $comment_id)
    {
        // kiểm tra đăng nhập
        if(!Auth::guard('user')->check()){
            Toastr::error('Vui lòng đăng nhập');
            return back();
        }

        $user = Auth::guard('user')->user();
        $comment = UserPostComment::findOrFail($comment_id);

        $reported = $comment->reports()
            ->firstWhere('user_id', $user->id);
        if($reported) {
            Toastr::error('Mỗi tài khoản chỉ được báo cáo 1 lần');
            return back();
        }

        $request['user_id'] = $user->id;
        $request['report_position'] = 2;
        $this->personalPageService->createReport($comment, $request->all());

        Toastr::success('Báo cáo thành công');
        return back();
    }
}
