<?php

namespace App\Http\Controllers\Admin\Report\Project;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Classified\ClassifiedReport;
use App\Models\MailBox;
use App\Models\ProjectComment;
use App\Models\ProjectReport;
use App\Models\ReportGroup;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCommentProjectController extends Controller
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
        if(isset($_GET['items'])){
            $items = $_GET['items'];
        }
        $list = ProjectComment::Wherehas('report_comment_project')
            ->where(function($query) use($request){
                $request->keyword!=''?$query->WhereRelation('user','username','like','%'.$request->keyword.'%'):null;
                $request->keyword!=''?$query->orWhereRelation('user_detail','phone_number','like','%'.$request->keyword.'%'):null;
            })->where(function($query) use($request){
                // lọc theo nội dung báo cáo
                $request->report_content!=''?$query->WhereRelation('report_comment_project','report_type','=',$request->report_content):null;
                // lọc theo ngày bắt đầu
                if($request->from_date!=''){
                    $from =strtotime(Carbon::parse($request->from_date));
                    $query->WhereRelation('report_comment_project','report_time','>',$from);
                }
                // lọc theo ngày kết thúc
                if($request->to_date!=''){
                    $to =strtotime(Carbon::parse($request->to_date)->addDay(1));
                    $query->WhereRelation('report_comment_project','report_time','<',$to);
                }
            })
            ->paginate($items);
        // get report_group
        $report_group = ReportGroup::where('type','=',2)->showed()->get();

        return view('Admin.Report.Project.comment-report',compact('list','report_group'));
    }
    // báo cáo sai
    public function report_false($id){
        $project = ProjectReport::where('project_comment_id','=',$id)->where('report_position',2)->get();
        if($project == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // $data =[
        //     'id'=>$id,
        // ];
        // Helper::create_admin_log(135,$data);
        foreach ($project as $item){
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
        $project_report = ProjectReport::where('project_comment_id','=',$id)->where('report_position',2)->get();
        if($project_report == null){
            Toastr::error("Bình luận không bị báo cáo");
            return back();
        }

        // tìm bình luận báo cáo
        $comment = ProjectComment::findOrFail($id);

        $comment->update([
            'is_show' => false
        ]);
        // Helper::create_admin_log(153,$data);

        // xác nhận trạng thái báo cáo đúng
        foreach ($project_report as $item){
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
    public function unblock_display($id)
    {
        $comment = ProjectComment::findOrFail($id);

        $comment->update([
            'is_show' => true
        ]);
        // Helper::create_admin_log(155,$data);

        Toastr::success("Hiển thị bình luận thành công");
        return back();
    }

    // Chặn tài khoản
    public function block_account($id){
        // tìm các báo cáo
        $project_report = ProjectReport::where('project_id','=',$id)->where('report_position',2)->get();
        if($project_report == null){
            Toastr::error("Bình luận không bị báo cáo");
            return back();
        }
        // tìm tin rao báo cáo
        $project = ProjectComment::findOrFail($id);

        if ($project->user) {
            $this->userService->blockUser($project->user);
        }

        // xác nhận trạng thái báo cáo đúng
        foreach ($project_report as $item){
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
    public function unblock_account($id)
    {
        // tìm dự án báo cáo
        $project = ProjectComment::findOrFail($id);

        if ($project->user) {
            $this->userService->unblockUser($project->user);
        }

        Toastr::success("Bỏ chặn tài khoản thành công");
        return back();
    }
    // cấm tài khoản
    public function forbidden($id){
        // tìm các báo cáo
        $project_report = ProjectReport::where('project_comment_id','=',$id)->where('report_position',2)->get();
        if($project_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm dự án báo cáo
        $project = ProjectComment::findOrFail($id);

        if ($project->user) {
            $this->userService->forbiddenUser($project->user);
        }
        // xác nhận trạng thái báo cáo đúng
        foreach ($project_report as $item){
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

        // tìm dự án báo cáo
        $project = ProjectComment::findOrFail($id);

        if ($project->user) {
            $this->userService->unforbiddenUser($project->user);
        }
        Toastr::success("Bỏ cấm tài khoản thành công");
        return back();
    }
    // xóa tài khoản
    public function delete_account($id){
        // tìm các báo cáo
        $project_report = ProjectReport::where('project_comment_id','=',$id)->where('report_position',2)->get();
        if($project_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm dự án báo cáo
        $project = ProjectComment::findOrFail($id);

        if ($project->user) {
            $this->userService->deleteUser($project->user);
        }
        // xác nhận trạng thái báo cáo đúng
        foreach ($project_report as $item){
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

        // tìm dự án báo cáo
        $project = ProjectComment::findOrFail($id);

        if ($project->user) {
            $this->userService->restoreUser($project->user);
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
                $project = ProjectReport::where('project_comment_id','=',$item)->where('report_position',2)->get();
                if($project == null) continue;
                // Helper::create_admin_log(36,$data);

                foreach ($project as $i){
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
                $project_report = ProjectReport::where('project_comment_id','=',$item)->where('report_position',2)->get();
                if($project_report == null){
                    Toastr::error("Đã xảy ra lỗi");
                    return back();
                }
                // tìm bình luận báo cáo
                $project = ProjectComment::find($item);
                if (!$project) continue;

                $project->update([
                    'is_show' => false
                ]);

                // Helper::create_admin_log(153,$data);

                // xác nhận trạng thái báo cáo đúng
                foreach ($project_report as $i){
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
                // tìm bình luận báo cáo
                $project = ProjectComment::find($item);
                if (!$project) continue;
                $project->update([
                    'is_show' => true
                ]);

                // Helper::create_admin_log(154,$data);
            }

            Toastr::success("Thành công");
            return back();
        }
        // Chặn tài khoản
        if($request->action_method == 'block_account'){
            foreach ($request->select_item as $item) {
                $project_report = ProjectReport::where('project_id','=',$item)->where('report_position',2)->get();
                if (!$project_report) continue;

                $project = ProjectComment::find($item);
                if (!$project || !$project->user) continue;

                $this->userService->blockUser($project->user);
                // xác nhận trạng thái báo cáo đúng
                foreach ($project_report as $i){
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
                // tìm dự án báo cáo
                $project = ProjectComment::find($item);
                if (!$project || !$project->user) continue;
                $this->userService->unblockUser($project->user);
            }

            Toastr::success("Thành công");
            return back();
        }
        // Cấm tài khoản
        if($request->action_method == 'forbidden'){
            foreach ($request->select_item as $item) {
                $project_report = ProjectReport::where('project_comment_id','=',$item)->where('report_position',2)->get();
                if (!$project_report) continue;
                // tìm tin rao báo cáo
                $project = ProjectComment::find($item);
                if (!$project || !$project->user) continue;
                $this->userService->forbiddenUser($project->user);

                // xác nhận trạng thái báo cáo đúng
                foreach ($project_report as $i){
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
                $project = ProjectComment::find($item);
                if (!$project || !$project->user) continue;
                $this->userService->unforbiddenUser($project->user);
            }

            Toastr::success("Thành công");
            return back();
        }

        // Xóa tài khoản
        if($request->action_method == 'delete'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $project_report = ProjectReport::where('project_comment_id','=',$item)->where('report_position',2)->get();
                if (!$project_report) continue;
                // tìm tin rao báo cáo
                $project = ProjectComment::find($item);
                if (!$project || !$project->user) continue;
                $this->userService->deleteUser($project->user);

                // xác nhận trạng thái báo cáo đúng
                foreach ($project_report as $i){
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
                $project = ProjectComment::find($item);
                if (!$project || !$project->user) continue;
                $this->userService->restoreUser($project->user);
            }

            Toastr::success("Thành công");
            return back();
        }
    }
    public function create_notification(Request $request){
        if($request->mail_title == '' ||$request->mail_content ==''){
            return response()->json('Vui lòng nhập đầy đủ các trường',500);
        }
        $classified_report = ClassifiedReport::where('classified_id','=',$request->id)->where('report_position',1)->get();
        if($classified_report == null){
            return response()->json('Đã xảy ra lỗi',500);
        }
        // tìm tin rao báo cáo
        $project = ProjectComment::find($request->id);
        if($project == null ){
            Toastr::error("Không tồn tại dự án");
            return response()->json('Không tồn tại dự án',500);
        }
        $data =[
            'object_type'=>0,
            'mail_title'=>$request->mail_title,
            'mail_content'=>$request->mail_content,
            'user_id'=>$project->user_id,
            'send_time'=>time(),
            'mailbox_type'=>'S',
            'created_by'=>Auth::guard('admin')->user()->id
        ];

        MailBox::create($data);
        // Helper::create_admin_log(141,$data);

        return response()->json('success',200);
    }
}
