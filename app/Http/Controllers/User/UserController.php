<?php

namespace App\Http\Controllers\User;

use App\CPU\ServiceFee;
use App\Http\Controllers\Controller;
use App\Models\User\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\User\ChooseAccountType;
use App\Http\Requests\User\Index\ChangeAvatarRequest;
use App\Http\Requests\User\Index\ChangeBackgroundRequest;
use App\Http\Requests\User\Index\ChangePassword;
use App\Http\Requests\User\Index\UpdateUserInfoRequest;
use App\Http\Requests\User\Index\UpdateUserSocialLink;
use App\Http\Requests\User\Index\UpdateDeployingProject;
use App\Http\Requests\User\Index\ProjectRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use App\CPU\InitUserAccount;
use App\Helpers\SystemConfig;
use Exception;
use App\Models\Classified\ClassifiedComment;
use App\Models\Banner\Banner;
use App\Models\Classified\Classified;
use App\Models\Group;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

/**
 *
 */
class UserController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    /**
     * Choosing user's account type when user is registered by social account
     * @param ChooseAccountType $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function choose_account_type(ChooseAccountType $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::guard('user')->user();
            InitUserAccount::init($user);

            $user->user_type_id = $request->user_type;
            $user->save();
            Auth::guard('user')->setUser($user);

            if ($request->user_type == 3 && $request->upload - license) {
                $businessLicenseURL = upload_image($request->file('upload-license'), SystemConfig::userDirectory());
                UserDetail::where('user_id', $user->id)->update(['business_license' => $businessLicenseURL]);

            }

            DB::commit();
            Toastr::success('Cập nhật thành công!');
            return redirect()->route('user.personal-info');

        } catch (Exception $exception) {
            DB::rollBack();
            Toastr::success('Cập nhật không thành công, vui lòng liên hệ Admin!');
            return redirect()->route('user.personal-info');

        }

        Toastr::success('Cập nhật không thành công, vui lòng liên hệ Admin!');
        return redirect()->back();
    }


    /**
     * User data statistics
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function countEqualData($query){
        $date_start = Carbon::now()->startOfMonth()->subMonth(1)->timestamp;
        $date_center = Carbon::now()->startOfMonth()->timestamp;
        $date_end = Carbon::now()->timestamp;
        $query_before = clone $query;
        $query_current = clone $query;
        $count_before = $query_before->whereBetween('created_at',[$date_start,$date_center])->count();
        $count_current = $query_current->whereBetween('created_at',[$date_center,$date_end])->count();
        $count = $query->count();
        $data = [
            'percent' => ($count_current-$count_before)/($count_before==0 ? 1 : $count_before)*100,
            'count_current' => $count_current,
            'count' => $count
        ];
        return $data;
    }
    public function index()
    {
        $user = Auth::guard('user')->user();
        $params['user_detail'] = DB::table('user_detail')->select('image_url')
            ->where('user_id', $user->id)->first();

        $customer = DB::table('customer')->where('user_id', $user->id);
        $params['customer'] = $this->countEqualData($customer);

        $rating = DB::table('user_rating')->where('user_id', $user->id);
        $params['rating'] = $this->countEqualData($rating);

        $comment = DB::table('classified_comment')->where('user_id', $user->id);
        $params['comment'] = $this->countEqualData($comment);

        $classified = DB::table('classified')->where('user_id', $user->id);
        $params['classified'] = $this->countEqualData($classified);

        //Đếm tin các chuyên mục và so với tháng trước
        $groups = Group::select('id','group_name','image_url','parent_id')
            ->whereHas('parent', function ($query) {
                $query->whereIn('group_url',['nha-dat-ban','nha-dat-cho-thue','can-mua','can-thue']);
            })->get();
        $classified_group_final = [];
        foreach($groups as $group){
            $data_group = $group->toArray();
            $classified_count = $this->countEqualData($group->classified->where('user_id', $user->id));
            $classified_group_merge = array_merge($data_group,$classified_count);
            array_push($classified_group_final,$classified_group_merge);
        }
        $params['groups'] = $classified_group_final;

        $params['classified_cus'] = Classified::select('classified_name', 'created_at','num_share','image_thumbnail','image_perspective','group_id','classified_url',
                'is_show', 'is_deleted', 'confirmed_status', 'expired_date',
                DB::raw("(select count(*) from customer where classified_id = classified.id) as customer")
            )
            ->where('user_id', $user->id)
            ->isDeleted()
            ->orderByDesc('customer')
            ->take(5)
            ->get();

        $params['classified_view'] = Classified::select(
                'classified_name', 'created_at','num_share','num_view','image_thumbnail','image_perspective','group_id','classified_url',
                'is_show', 'is_deleted', 'confirmed_status', 'expired_date',
            )
            ->where('user_id', $user->id)
            ->isDeleted()
            ->orderByDesc('num_view')
            ->take(5)
            ->get();

        $params['classified_comment'] = ClassifiedComment::where('user_id', $user->id)
            ->take(5)
            ->get();

        $params['banner'] = Banner::where('user_id', $user->id)
            ->take(5)
            ->get();

        return view('user.index', $params);

    }

    /**
     * User account information
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function personal_info()
    {
        return view('user.user.index');

    }

    /**
     * Update user's social link
     * @param UpdateUserSocialLink $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_update_social_link(UpdateUserSocialLink $request)
    {
        $social_data = [];
        if ($request->facebook) {
            $social_data['facebook'] = $request->facebook;
        }
        if ($request->twitter) {
            $social_data['twitter'] = $request->twitter;
        }
        if ($request->youtube) {
            $social_data['youtube'] = $request->youtube;
        }
        $user = Auth::guard('user')->user();

        if($social_data){
            $update_status = DB::table('user_detail')
                ->where('user_id', $user->id)
                ->update($social_data);
            create_user_log(3, '');
        }

        return redirect()->back();
    }

    /**
     * Update project deploying
     * @param UpdateDeployingProject $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_update_deploying_project(UpdateDeployingProject $request)
    {
        # check block/forbidden for post
        $blockMessage = user_blocked('cập nhật dự án');
        if ($blockMessage) {
            Toastr::error($blockMessage);
            return back();
        }

        $user = Auth::guard('user')->user();
        $this->userService->syncProjects($user, $request->projects);

        Toastr::success('Cập nhật dự án thành công.');
        return redirect()->back();
    }

    /**
     * Delete account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAccount()
    {
        $user = Auth::guard('user')->user();

        $dayDeleted = $user->logContents()
            ->select('user_log_content.*')
            ->leftJoin('user_log', 'user_log.id', '=', 'user_log_content.log_id')
            ->where('user_log_content.log_time', '>', now()->startOfDay()->timestamp)
            ->firstWhere('user_log.key', 'user-delete-account');

        if ($dayDeleted) {
            Toastr::error('Chỉ được xóa tài khoản 1 lần/ngày');
            return back();
        }

        $user->is_deleted = 1;
        $user->delete_time = time();
        $user->save();

        createUserLog('user-delete-account');

        Toastr::success('Xóa tài khoản thành công!');
        return redirect()->back();
    }

    /**
     * revert account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreAccount()
    {
        $user = Auth::guard('user')->user();

        if ($user->canRestore()) {
            $user->update([
                'is_deleted' => false,
                'delete_time' => null
            ]);

            createUserLog('user-restore-account');

            Toastr::success('Khôi phục tài khoản thành công');
            return back();
        }

        Toastr::success('Tài khoản đã hết hạn hủy xóa');
        return back();
    }

    /**
     * Request to create project
     * @param ProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_project_request(ProjectRequest $request)
    {
        $user = Auth::guard('user')->user();
        $data = [
            'project_name' => $request->project_name,
            'investor' => $request->investor,
            'address' => $request->address,
            'province_id' => $request->pr_province,
            'district_id' => $request->pr_district,
            'ward_id' => $request->pr_ward,
        ];
        $this->userService->createProjectRequest($user, $data);

        Toastr::success('Gửi yêu cầu thành công');
        return redirect()->back();
    }

    /**
     * Change password
     * @param ChangePassword $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_password_change(ChangePassword $request)
    {
        $user = Auth::guard('user')->user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        create_user_log(4);
        Toastr::success('Đổi mật khẩu thành công');
        return redirect()->back();
    }

    /**
     * Update personal information
     * @param UpdateUserInfoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_update_personal_info(UpdateUserInfoRequest $request)
    {
        # check block/forbidden for post
        $blockMessage = user_blocked('cập nhật thông tin');
        if ($blockMessage) {
            Toastr::error($blockMessage);
            return back();
        }

        $user = Auth::guard('user')->user();

        $this->userService->update($user, $request->all());

        Toastr::success('Cập nhật thông tin thành công');
        return redirect()->back();
    }

    /**
     * Update background
     * @param ChangeBackgroundRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_background(ChangeBackgroundRequest $request)
    {
        $user = Auth::guard('user')->user();

        $currentBackground = DB::table('user_detail')->where('user_id', $user->id)->value('background_url');
        File::delete($currentBackground);
        $newBackground = upload_image($request->background_img, SystemConfig::userDirectory(), 600, 400);
        DB::table('user_detail')->where('user_id', $user->id)->update(['background_url' => $newBackground]);
        create_user_log(10, '');
        Toastr::success('Đổi ảnh bìa thành công');
        return redirect()->back();
    }

    /**
     * Update avatar
     * @param ChangeAvatarRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_avatar(ChangeAvatarRequest $request)
    {
        # check block/forbidden for comment
        $blockMessage = user_blocked('thay avatar');

        if ($blockMessage) {
            Toastr::error($blockMessage);
            return redirect()->back();
        }

        $user = Auth::guard('user')->user();
        $this->userService->updateAvatar($user, $request->upload_cover);

        Toastr::success('Đổi ảnh đại diện thành công');
        return redirect()->back();
    }

    /**
     * User logout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Session::flush();
        Auth::guard('user')->logout();
        return redirect()->route('home.index');
    }

    /**
     * Show user's gallery by FileManager
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function gallery()
    {
        session_start();
        return view('user.gallery.index');
    }


    /**
     * List reference accounts
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function user_ref_list()
    {

        $user = Auth::guard('user')->user();
        $params['user_ref_link'] = route('user-ref-link', $user->user_code);
        $params['total_user_ref'] = DB::table('user')->where('user_ref_id', $user->id)->count();
        $params['user_refs'] = DB::table('user')->select('user.id', 'ud.fullname', 'user.created_at')
            ->where('user.user_ref_id', $user->id)
            ->leftJoin('user_detail as ud', 'user.id', 'ud.user_id')
            ->orderBy('id', 'desc')
            ->paginate(20);
        $params['total_coin_ref'] = DB::table('user_coin_ref_receipt')->where('user_id', $user->id)->sum('receipt_coin');
        return View('user.userref.index', $params);
    }


    /**
     * Visitor access website by user's reference link
     * @param $user_code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function user_ref_link($user_code)
    {
        $check_user_id = DB::table('user')->where('user_code', $user_code)->value('id');
        $user = Auth::guard('user')->user();
        if ($user) {
            return redirect()->route('user.index');
        }

        if (!$check_user_id) {
            Toastr::error('Link giới thiệu không khả dụng');
        }

        session(['user_ref_id' => $check_user_id]);
        Session::flash('popup_display', '#login');
        return redirect()->route('home.index');
    }

    /**
     * User upgrade account
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upgrade_account(Request $request)
    {

        if ($request->inp_user_highlight) {
            $user = Auth::guard('user')->user();
            $upgrade_data = [
                'is_highlight' => 0,
                'highlight_time' => null,
            ];
            DB::table('user')->where('id', $user->id)->update($upgrade_data);
            create_user_log(11, '');
            Toastr::success('Hủy nâng cấp tài khoản thành công!');
            return redirect()->back();
        }
        if (!$request->inp_user_highlight) {
            $serviceStatus = ServiceFee::upgrade_account();
            if ($serviceStatus['status']) {
                create_user_log(12, '');
                Toastr::success($serviceStatus['message']);
                return redirect()->back();
            } else {
                Toastr::error($serviceStatus['message']);
                return redirect()->back();
            }

        }

        Toastr::error('Thao tác không thành công, vui lòng liên hệ Admin!');
        return redirect()->back();

    }

}
