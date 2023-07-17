<?php

namespace App\Http\Controllers\Admin\Classified;

use App\Http\Controllers\Controller;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedReport;
use App\Models\MailBox;
use App\Models\ReportGroup;
use App\Services\UserService;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class ClassifiedReportController extends Controller
{
    private UserService $userService;

    /**
     * inject UserService into UserController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }

    // danh sách báo cáo
    public function list(Request $request){
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        $list = Classified::Wherehas('report_classified')
            ->where(function($query) use($request){
                $request->keyword!=''?$query->WhereRelation('user','username','like','%'.$request->keyword.'%'):null;
                $request->keyword!=''?$query->orWhere('classified_name','like','%'.$request->keyword.'%'):null;
                $request->keyword!=''?$query->orWhereRelation('user_detail','phone_number','like','%'.$request->keyword.'%'):null;
            })->where(function($query) use($request){
                // lọc theo nội dung báo cáo
                $request->report_content!=''?$query->WhereRelation('report_classified','report_type','=',$request->report_content):null;
                // lọc theo ngày bắt đầu
                if($request->from_date!=''){
                    $from =strtotime(Carbon::parse($request->from_date));
                    $query->WhereRelation('report_classified','report_time','>',$from);
                }
                // lọc theo ngày kết thúc
                if($request->to_date!=''){
                    $to =strtotime(Carbon::parse($request->to_date)->addDay(1));
                    $query->WhereRelation('report_classified','report_time','<',$to);
                }
            })
            ->paginate($items);
        // get report_group
        $report_group = ReportGroup::where('type','=',1)
            ->showed()
            ->get();

        return view('Admin.Classified.ReportClassified.ReportList',compact('list','report_group'));
    }
    // báo cáo sai
    public function report_false($id){
        $classified = ClassifiedReport::where('classified_id','=',$id)->where('report_position',1)->get();
        if($classified == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        foreach ($classified as $item){
            $item->update([
                'report_result' => 0,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }
        // Helper::create_admin_log(36,$classified);
        Toastr::success("Cập nhật trạng thái thành công");
        return back();
    }
    // chặn hiển thị
    public function block_display($id){
        // tìm các báo cáo
        $classified_report = ClassifiedReport::where('classified_id','=',$id)->where('report_position',1)->get();
        if($classified_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm tin rao báo cáo
        $classified = Classified::findOrFail($id);

        $classified->update([
            'is_show' => 0,
            'is_block' => 1
        ]);

        // $data = ['is_show'=>0,'is_block'=>1];
        // Helper::create_admin_log(37,$data);
        // xác nhận trạng thái báo cáo đúng
        foreach ($classified_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        Toastr::success("Chặn hiển thị thành công");
        return back();
    }
    // Khôi phục hiển thị
    public function unblock_display($id){
        // tìm tin rao báo cáo
        $classified = Classified::findOrFail($id);
        $classified->update([
            'is_show' => 1,
            'is_block' => 0
        ]);

        // Helper::create_admin_log(38,['is_show'=>1,'is_block'=>0]);

        Toastr::success("Khôi phục hiển thị thành công");
        return back();
    }
    // Chặn tài khoản
    public function block_account($id){
        // tìm các báo cáo
        $classified_report = ClassifiedReport::where('classified_id','=',$id)->where('report_position',1)->get();
        if($classified_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm tin rao báo cáo
        $classified = Classified::findOrFail($id);

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
        $classified = Classified::findOrFail($id);

        if ($classified->user) {
            $this->userService->unblockUser($classified->user);
        }

        Toastr::success("Bỏ chặn tài khoản thành công");
        return back();
    }
    // cấm tài khoản
    public function forbidden($id){
        // tìm các báo cáo
        $classified_report = ClassifiedReport::where('classified_id','=',$id)->where('report_position',1)->get();
        if($classified_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm tin rao báo cáo
        $classified = Classified::findOrFail($id);

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
        $classified = Classified::findOrFail($id);

        if ($classified->user) {
            $this->userService->unforbiddenUser($classified->user);
        }

        Toastr::success("Bỏ cấm tài khoản thành công");
        return back();
    }
    // xóa tài khoản
    public function delete_account($id){
        // tìm các báo cáo
        $classified_report = ClassifiedReport::where('classified_id','=',$id)->where('report_position',1)->get();
        if($classified_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // tìm tin rao báo cáo
        $classified = Classified::findOrFail($id);
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
        $classified = Classified::findOrFail($id);
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
                $classified = ClassifiedReport::where('classified_id','=',$item)->where('report_position',1)->get();
                if (!$classified) continue;
                // Helper::create_admin_log(36,$classified);

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
                $classified_report = ClassifiedReport::where('classified_id','=',$item)->where('report_position',1)->get();
                if (!$classified_report) continue;
                // tìm tin rao báo cáo
                $classified = Classified::find($item);
                if($classified == null ){
                    Toastr::error("Không tồn tại tin rao");
                    return back();
                }
                $classified->update([
                    'is_show' => 0,
                    'is_block' => 1,
                ]);

                // Helper::create_admin_log(37,['is_show'=>0,'is_block'=>1]);
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
                $classified = Classified::find($item);
                if (!$classified) continue;

                $classified->update([
                    'is_show' => 1,
                    'is_block' => 0
                ]);
                // Helper::create_admin_log(38,['is_show'=>1,'is_block'=>0]);
            }
            Toastr::success("Thành công");
            return back();
        }
        // Chặn tài khoản
        if($request->action_method == 'block_account'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $classified_report = ClassifiedReport::where('classified_id','=',$item)->where('report_position',1)->get();
                if(!$classified_report) continue;
                // tìm tin rao báo cáo
                $classified = Classified::find($item);
                if (!$classified) continue;

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
                $classified = Classified::find($item);
                if (!$classified) continue;

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
                $classified_report = ClassifiedReport::where('classified_id','=',$item)->where('report_position',1)->get();
                if(!$classified_report) continue;
                // tìm tin rao báo cáo
                $classified = Classified::find($item);
                if (!$classified) continue;

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
                $classified = Classified::find($item);
                if (!$classified) continue;

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
                $classified_report = ClassifiedReport::where('classified_id','=',$item)->where('report_position',1)->get();
                if(!$classified_report) continue;
                // tìm tin rao báo cáo
                $classified = Classified::find($item);
                if (!$classified) continue;

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
                $classified = Classified::find($item);
                if (!$classified) continue;

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
        $classified_report = ClassifiedReport::where('classified_id','=',$request->id)->where('report_position',1)->get();
        if($classified_report == null){
            return response()->json('Đã xảy ra lỗi',500);
        }
        // tìm tin rao báo cáo
        $classified = Classified::find($request->id);
        if($classified == null ){
            return response()->json('Không tồn tại tin rao',500);

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
        // Helper::create_admin_log(45,$data);

        MailBox::create($data);

        return response()->json('success',200);
    }

}
