<?php

namespace App\Http\Controllers\Admin\Report\Account;

use App\Http\Controllers\Controller;
use App\Models\MailBox;
use App\Models\ReportGroup;
use App\Models\User\UserPost;
use App\Models\User\UserPostReport;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportPostAccountController extends Controller
{
    private UserService $userService;

    /**
     * inject UserService into UserController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function list(Request $request){
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        $list = UserPost::Wherehas('report_post')
            ->where(function($query) use($request){
                $request->keyword!=''?$query->WhereRelation('user','username','like','%'.$request->keyword.'%'):null;
                $request->keyword!=''?$query->orWhereRelation('user','phone_number','like','%'.$request->keyword.'%'):null;
            })->where(function($query) use($request){
                // lọc theo nội dung báo cáo
                $request->report_content!=''?$query->WhereRelation('report_post','report_type','=',$request->report_content):null;
                // lọc theo ngày bắt đầu
                if($request->from_date!=''){
                    $from =strtotime(Carbon::parse($request->from_date));
                    $query->WhereRelation('report_post','report_time','>',$from);
                }
                // lọc theo ngày kết thúc
                if($request->to_date!=''){
                    $to =strtotime(Carbon::parse($request->to_date)->addDay(1));
                    $query->WhereRelation('report_post','report_time','<',$to);
                }
            })
            ->with('user')
            ->paginate($items);
        // get report_group
        $report_group = ReportGroup::showed()->where('type','=',1)->get();
        return view('Admin.Report.Account.ReportPost',compact('list','report_group'));
    }
    // báo cáo sai
    public function report_false($id){
        $user_post_report = UserPostReport::where('user_post_id','=',$id)->where('report_position',1)->get();
        if($user_post_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // Helper::create_admin_log(145,$data);
        foreach ($user_post_report as $item){
            $item->update([
                'report_result' => 0,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }

        Toastr::success("Cập nhật trạng thái thành công");
        return back();
    }
    // chặn hiển thị
    public function block_display($id){
        // tìm các báo cáo
        $user_post_report = UserPostReport::where('user_post_id','=',$id)->where('report_position',1)->get();
        if($user_post_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm bài viết báo cáo
        $user_post = UserPost::findOrFail($id);
        $user_post->update([
            'is_show' => 0
        ]);
        // Helper::create_admin_log(146,$data);

        // xác nhận trạng thái báo cáo đúng
        foreach ($user_post_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }

        Toastr::success("Chặn hiển thị thành công");
        return back();
    }
    // Khôi phục hiển thị
    public function unblock_display($id){
        $user_post = UserPost::findOrFail($id);
        $user_post->update([
            'is_show' => 1
        ]);
        // Helper::create_admin_log(147,$data);

        Toastr::success("Khôi phục hiển thị thành công");
        return back();
    }
    // Chặn tài khoản
    public function block_account($id){
        // tìm các báo cáo
        $user_post_report = UserPostReport::where('user_post_id','=',$id)->where('report_position',1)->get();
        if($user_post_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm bài viết báo cáo
        $user_post = UserPost::findOrFail($id);
        if ($user_post->user) {
            $this->userService->blockUser($user_post->user);
        }

        // xác nhận trạng thái báo cáo đúng
        foreach ($user_post_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }
        Toastr::success("Chặn tài khoản thành công");
        return back();
    }
    // Bỏ chặn tài khoản
    public function unblock_account($id){

        // tìm bài viết báo cáo
        $user_post = UserPost::findOrFail($id);
        if ($user_post->user) {
            $this->userService->unblockUser($user_post->user);
        }


        Toastr::success("Bỏ chặn tài khoản thành công");
        return back();
    }
    // cấm tài khoản
    public function forbidden($id){
        // tìm các báo cáo
        $user_post_report = UserPostReport::where('user_post_id','=',$id)->where('report_position',1)->get();
        if($user_post_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        $user_post = UserPost::findOrFail($id);
        if ($user_post->user) {
            $this->userService->forbiddenUser($user_post->user);
        }

        // xác nhận trạng thái báo cáo đúng
        foreach ($user_post_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }
        Toastr::success("Cấm tài khoản thành công");
        return back();
    }
    // Bỏ cấm tài khoản
    public function unforbidden($id){
        $user_post = UserPost::findOrFail($id);
        if ($user_post->user) {
            $this->userService->unforbiddenUser($user_post->user);
        }

        Toastr::success("Bỏ cấm tài khoản thành công");
        return back();
    }
    // xóa tài khoản
    public function delete_account($id){
        // tìm các báo cáo
        $user_post_report = UserPostReport::where('user_post_id','=',$id)->where('report_position',1)->get();
        if($user_post_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm bài viết báo cáo
        $user_post = UserPost::findOrFail($id);
        if ($user_post->user) {
            $this->userService->deleteUser($user_post->user);
        }

        // xác nhận trạng thái báo cáo đúng
        foreach ($user_post_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }
        Toastr::success("Xóa tài khoản thành công");
        return back();
    }
    // Khôi phục tài khoản
    public function undelete_account($id){

        // tìm bài viết báo cáo
        $user_post = UserPost::findOrFail($id);
        if ($user_post->user) {
            $this->userService->restoreUser($user_post->user);
        }

        Toastr::success("Khôi phục tài khoản thành công");
        return back();
    }
    public function list_action(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        // báo cáo sai
        if($request->action_method == 'report_false'){
            foreach ($request->select_item as $item) {
                $user_post_report = UserPostReport::where('user_post_id','=',$item)->where('report_position',1)->get();
                if($user_post_report == null) continue;
                // Helper::create_admin_log(145,$data);

                foreach ($user_post_report as $i){
                    $i->update([
                        'report_result' => 0,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // chặn hiển thị
        if($request->action_method == 'block_display'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $user_post_report = UserPostReport::where('user_post_id','=',$item)->where('report_position',1)->get();
                if(!$user_post_report) continue;

                $user_post = UserPost::find($item);
                if(!$user_post) continue;

                $user_post->update([
                    'is_show' => 0
                ]);
                // Helper::create_admin_log(146,$data);

                // xác nhận trạng thái báo cáo đúng
                foreach ($user_post_report as $i){
                    $i->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Khôi phục hiển thị
        if($request->action_method == 'unblock_display'){
            foreach ($request->select_item as $item) {
                // tìm bài viết báo cáo
                $user_post = UserPost::find($item);
                if(!$user_post) continue;

                $user_post->update([
                    'is_show' => 1
                ]);
                // Helper::create_admin_log(147,$data);
            }

            Toastr::success("Thành công");
            return back();
        }
        // Chặn tài khoản
        if($request->action_method == 'block_account'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $user_post_report = UserPostReport::where('user_post_id','=',$item)->where('report_position',1)->get();
                if($user_post_report == null){
                    Toastr::error("Đã xảy ra lỗi");
                    return back();
                }
                // tìm bài viết báo cáo
                $user_post = UserPost::find($item);
                if (!$user_post) continue;

                if ($user_post->user) {
                    $this->userService->blockUser($user_post->user);
                }

                // xác nhận trạng thái báo cáo đúng
                foreach ($user_post_report as $i){
                    $i->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Bỏ chặn tài khoản
        if($request->action_method == 'unblock_account'){
            foreach ($request->select_item as $item) {
                // tìm bài viết báo cáo
                $user_post = UserPost::find($item);
                if (!$user_post) continue;

                if ($user_post->user) {
                    $this->userService->unblockUser($user_post->user);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Cấm tài khoản
        if($request->action_method == 'forbidden'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $user_post_report = UserPostReport::where('user_post_id','=',$item)->where('report_position',1)->get();
                if($user_post_report == null){
                    Toastr::error("Đã xảy ra lỗi");
                    return back();
                }
                // tìm bài viết báo cáo
                $user_post = UserPost::find($item);
                if (!$user_post) continue;

                if ($user_post->user) {
                    $this->userService->forbiddenUser($user_post->user);
                }

                // xác nhận trạng thái báo cáo đúng
                foreach ($user_post_report as $i){
                    $i->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Bỏ cấm tài khoản
        if($request->action_method == 'unforbidden'){
            foreach ($request->select_item as $item) {
                // tìm bài viết báo cáo
                $user_post = UserPost::find($item);
                if (!$user_post) continue;

                if ($user_post->user) {
                    $this->userService->unforbiddenUser($user_post->user);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Xóa tài khoản
        if($request->action_method == 'delete'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $user_post_report = UserPostReport::where('user_post_id','=',$item)->where('report_position',1)->get();
                if($user_post_report == null){
                    Toastr::error("Đã xảy ra lỗi");
                    return back();
                }
                // tìm bài viết báo cáo
                $user_post = UserPost::find($item);
                if (!$user_post) continue;

                if ($user_post->user) {
                    $this->userService->deleteUser($user_post->user);
                }

                // xác nhận trạng thái báo cáo đúng
                foreach ($user_post_report as $i){
                    $i->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Khôi phục tài khoản
        if($request->action_method == 'restore'){
            foreach ($request->select_item as $item) {
                // tìm bài viết báo cáo
                $user_post = UserPost::find($item);
                 if (!$user_post) continue;

                if ($user_post->user) {
                    $this->userService->restoreUser($user_post->user);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
    }
    public function create_notification(Request $request){
//        dd($request->all());
        if($request->mail_title == '' ||$request->mail_content ==''){
            return response()->json('Vui lòng nhập đầy đủ các trường',500);
        }
        $user_post = UserPost::find($request->id);
        if($user_post == null ){
            Toastr::error("Không tồn tại bài viết");
            return back();
        }
        $data =[
            'object_type'=>0,
            'mail_title'=>$request->mail_title,
            'mail_content'=>$request->mail_content,
            'user_id'=>$user_post->user_id,
            'send_time'=>time(),
            'mailbox_type'=>'S',
            'created_by'=>Auth::guard('admin')->user()->id
        ];
        MailBox::create($data);

        return response()->json('success',200);
    }
}
