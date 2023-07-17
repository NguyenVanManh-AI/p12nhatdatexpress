<?php

namespace App\Http\Controllers\Admin\Report\Classified;

use App\Http\Controllers\Controller;
use App\Models\Classified\ClassifiedComment;
use App\Models\Classified\ClassifiedReport;
use App\Models\MailBox;
use App\Models\ReportGroup;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCommentClassifiedController extends Controller
{
    private UserService $userService;

    /**
     * inject UserService into UserController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }

    // danh sách báo cáo bình luận tin rao
    public function list(Request $request){
        // phân trang
        $items = 10;
        if(isset($_GET['items'])){
            $items = $_GET['items'];
        }
        $list = ClassifiedComment::Wherehas('report_comment_classified')
            ->where(function($query) use($request){
                $request->keyword!=''?$query->WhereRelation('user','username','like','%'.$request->keyword.'%'):null;
                $request->keyword!=''?$query->orWhereRelation('user_detail','phone_number','like','%'.$request->keyword.'%'):null;
            })->where(function($query) use($request){
                // lọc theo nội dung báo cáo
                $request->report_content!=''?$query->WhereRelation('report_comment_classified','report_type','=',$request->report_content):null;
                // lọc theo ngày bắt đầu
                if($request->from_date!=''){
                    $from =strtotime(Carbon::parse($request->from_date));
                    $query->WhereRelation('report_comment_classified','report_time','>',$from);
                }
                // lọc theo ngày kết thúc
                if($request->to_date!=''){
                    $to =strtotime(Carbon::parse($request->to_date)->addDay(1));
                    $query->WhereRelation('report_comment_classified','report_time','<',$to);
                }
            })
            ->paginate($items);

        $report_group = ReportGroup::where('type','=',2)->showed()->get();
        return view('Admin.Report.Classified.comment-report',compact('list','report_group'));
    }

    // báo cáo sai
    public function report_false($id){
        $classified = ClassifiedReport::where('classified_comment_id','=',$id)->where('report_position',2)->get();
        if($classified == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // $data = [
        //     'id'=>$id
        // ];
        // Helper::create_admin_log(148,$data);
        foreach ($classified as $item){
            $item->update([
                'report_result' => 0,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id,
            ]);
        }
        Toastr::success("Cập nhật trạng thái thành công");
        return back();
    }
    // chặn hiển thị
    public function block_display($id){
        // tìm các báo cáo
        $classified_report = ClassifiedReport::where('classified_comment_id','=',$id)->where('report_position',2)->get();
        if($classified_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm tin rao báo cáo
        $comment = ClassifiedComment::findOrFail($id);
        $comment->update([
            'is_show' => false
        ]);
        // Helper::create_admin_log(149,$data);

        // xác nhận trạng thái báo cáo đúng
        foreach ($classified_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        Toastr::success("Ẩn bình luận thành công");
        return back();
    }

    // Khôi phục hiển thị
    public function unblock_display($id){
        // tìm tin rao báo cáo
        $comment = ClassifiedComment::findOrFail($id);
        $comment->update([
            'is_show' => true
        ]);
        // Helper::create_admin_log(150,$data);

        Toastr::success("Hiển thị bình luận thành công");
        return back();
    }

    // Chặn tài khoản
    public function block_account($id){
        // tìm các báo cáo
        $classified_report = ClassifiedReport::where('classified_comment_id','=',$id)->where('report_position',2)->get();
        if($classified_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm bình luận tin rao báo cáo
        $classified = ClassifiedComment::findOrFail($id);
        if ($classified->user) {
            $this->userService->blockUser($classified->user);
        }

        // xác nhận trạng thái báo cáo đúng
        foreach ($classified_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        Toastr::success("Chặn tài khoản thành công");
        return back();
    }

    // Bỏ chặn tài khoản
    public function unblock_account($id){

        // tìm tin rao báo cáo
        $classified = ClassifiedComment::findOrFail($id);

        if ($classified->user) {
            $this->userService->unblockUser($classified->user);
        }

        Toastr::success("Bỏ chặn tài khoản thành công");
        return back();
    }

    // cấm tài khoản
    public function forbidden($id){
        // tìm các báo cáo
        $classified_report = ClassifiedReport::where('classified_comment_id','=',$id)->where('report_position',2)->get();
        if($classified_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm tin rao báo cáo
        $classified = ClassifiedComment::findOrFail($id);

        if ($classified->user) {
            $this->userService->forbiddenUser($classified->user);
        }
        // xác nhận trạng thái báo cáo đúng
        foreach ($classified_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        Toastr::success("Cấm tài khoản thành công");
        return back();
    }
    // Bỏ cấm tài khoản
    public function unforbidden($id){

        // tìm tin rao báo cáo
        $classified = ClassifiedComment::findOrFail($id);

        if ($classified->user) {
            $this->userService->unforbiddenUser($classified->user);
        }
        Toastr::success("Bỏ cấm tài khoản thành công");
        return back();
    }
    // xóa tài khoản
    public function delete_account($id){
        // tìm các báo cáo
        $classified_report = ClassifiedReport::where('classified_comment_id','=',$id)->where('report_position',2)->get();
        if($classified_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm tin rao báo cáo
        $classified = ClassifiedComment::findOrFail($id);

        if ($classified->user) {
            $this->userService->deleteUser($classified->user);
        }
        // xác nhận trạng thái báo cáo đúng
        foreach ($classified_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id,
            ]);
        }
        Toastr::success("Xóa tài khoản thành công");
        return back();
    }

    // Khôi phục tài khoản
    public function undelete_account($id){

        // tìm tin rao báo cáo
        $classified = ClassifiedComment::findOrFail($id);

        if ($classified->user) {
            $this->userService->restoreUser($classified->user);
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
                $classified = ClassifiedReport::where('classified_comment_id','=',$item)->where('report_position',2)->get();
                if($classified == null) continue;
                // Helper::create_admin_log(148,$data);
                
                foreach ($classified as $i){
                    $i->update([
                        'report_result' => 0,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }

        // Chặn hiển thị
        if($request->action_method == 'block_display'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $classified_report = ClassifiedReport::where('classified_comment_id','=',$item)->where('report_position',2)->get();
                if($classified_report == null) continue;
                // tìm tin rao báo cáo
                $classified = ClassifiedComment::find($item);
                if (!$classified) continue;

                $classified->update([
                    'is_show' => 0
                ]);
                // Helper::create_admin_log(149,$data);

                // xác nhận trạng thái báo cáo đúng
                foreach ($classified_report as $i){
                    $i->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }

            Toastr::success("Thành công");
            return back();
        }

        // Khôi phục hiển thị
        if($request->action_method == 'unblock_display'){
            foreach ($request->select_item as $item) {
                // tìm tin rao báo cáo
                $classified = ClassifiedComment::find($item);
                if (!$classified) continue;

                $classified->update([
                    'is_show' => 1
                ]);

                // Helper::create_admin_log(150,$data);
            }

            Toastr::success("Thành công");
            return back();
        }

        // Chặn tài khoản
        if($request->action_method == 'block_account'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $classified_report = ClassifiedReport::where('classified_comment_id','=',$item)->where('report_position',2)->get();
                if(!$classified_report) continue;
                // tìm bình luận tin rao báo cáo
                $classified = ClassifiedComment::find($item);
                if(!$classified) continue;

                if ($classified->user) {
                    $this->userService->blockUser($classified->user);
                }
                // xác nhận trạng thái báo cáo đúng
                foreach ($classified_report as $i){
                    $i->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Bỏ chặn tài khoản
        if($request->action_method == 'unblock_account'){
            foreach ($request->select_item as $item) {
                // tìm tin rao báo cáo
                $classified = ClassifiedComment::find($item);
                if(!$classified) continue;

                if ($classified->user) {
                    $this->userService->unblockUser($classified->user);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Cấm tài khoản
        if($request->action_method == 'forbidden'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $classified_report = ClassifiedReport::where('classified_comment_id','=',$item)->where('report_position',2)->get();
                if(!$classified_report) continue;
                // tìm tin rao báo cáo
                $classified = ClassifiedComment::find($item);
                if(!$classified) continue;

                if ($classified->user) {
                    $this->userService->forbiddenUser($classified->user);
                }
                // xác nhận trạng thái báo cáo đúng
                foreach ($classified_report as $i){
                    $i->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Bỏ cấm tài khoản
        if($request->action_method == 'unforbidden'){
            foreach ($request->select_item as $item) {
                // tìm tin rao báo cáo
                $classified = ClassifiedComment::find($item);
                if(!$classified) continue;

                if ($classified->user) {
                    $this->userService->unforbiddenUser($classified->user);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Xóa tài khoản
        if($request->action_method == 'delete'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $classified_report = ClassifiedReport::where('classified_comment_id','=',$item)->where('report_position',2)->get();
                if(!$classified_report) continue;
                // tìm tin rao báo cáo
                $classified = ClassifiedComment::find($item);
                if(!$classified) continue;

                if ($classified->user) {
                    $this->userService->deleteUser($classified->user);
                }

                // xác nhận trạng thái báo cáo đúng
                foreach ($classified_report as $i){
                    $i->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Khôi phục tài khoản
        if($request->action_method == 'restore'){
            foreach ($request->select_item as $item) {
                // tìm tin rao báo cáo
                $classified = ClassifiedComment::find($item);
                if(!$classified) continue;

                if ($classified->user) {
                    $this->userService->restoreUser($classified->user);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
    }
    public function create_notification(Request $request){

        if($request->mail_title == '' ||$request->mail_content ==''){
            return response()->json('Vui lòng nhập đầy đủ các trường',500);
        }
        // tìm bình luận tin rao báo cáo
        $classified = ClassifiedComment::find($request->id);
        if($classified == null ){
            return response()->json('Không tồn tại bình luận',500);
        }
        $data =[
            'object_type'=>0,
            'mail_title'=>$request->mail_title,
            'mail_content'=>$request->mail_content,
            'user_id'=>$classified->user_id,
            'send_time'=>time(),
            'mailbox_type'=>'S',
            'created_by'=>Auth::guard('admin')->user()->id
        ];
        MailBox::create($data);

        return response()->json('success',200);
    }
}
