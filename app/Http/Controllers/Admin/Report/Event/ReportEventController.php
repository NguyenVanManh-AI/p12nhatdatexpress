<?php

namespace App\Http\Controllers\Admin\Report\Event;

use App\Helpers\Helper;
use App\Models\Classified\ClassifiedReport;
use App\Models\Event\Event;
use App\Http\Controllers\Controller;
use App\Models\Event\EventReport;
use App\Models\MailBox;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportEventController extends Controller
{
    private UserService $userService;

    /**
     * inject UserService into UserController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }
    //------------------------------------------------------------------------LIST---------------------------------------------------------------------//
    /** Danh sach bao cao
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request)
    {
        // Paginate
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        // Lấy ra các lý do báo cáo cho chức năng lọc
        $getReportReason = DB::table('report_group')->where('type', 1)->orderby('id', 'desc')->get();

        //lấy ra danh sách báo cáo
        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $list = Event::Wherehas('report_event', function ($q) use ($request) {
            // Lọc với nội dung báo cáo
            if ($request->report_content != '')
                $q->WhereRelation('report_group', 'report_type', '=', $request->report_content);

            // Lọc với ngày bắt đầu
            if ($request->from_date != '')
                $q->where('report_time', '>', date(strtotime($request->from_date)));

            // Lọc với ngày kết thúc
            if ($request->to_date != '') {
                $end_of_date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay()->toDateTimeString();
                $q->where('report_time', '<', date(strtotime($end_of_date)));
            }
        })
            ->filter($params)
            ->paginate($items);

        return view('Admin.Report.Event.report', compact('list', 'getReportReason'));
    }
// báo cáo sai
    public function report_false($id){
        $event_report = EventReport::where('event_id','=',$id)->get();
        if($event_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        // Helper::create_admin_log(151,$data);

        foreach ($event_report as $item){
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
        $event = Event::findOrFail($id);
        $event_report = EventReport::where('event_id','=',$id)->get();
        if($event_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        $event->update([
            'is_block' => 1
        ]);
        // Helper::create_admin_log(95,$data);

        // xác nhận trạng thái báo cáo đúng
        foreach ($event_report as $item){
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
        $event = Event::findOrFail($id);
        $event->update([
            'is_block' => 0
        ]);
        // Helper::create_admin_log(96,$data);

        Toastr::success("Khôi phục hiển thị thành công");
        return back();
    }
    // Chặn tài khoản
    public function block_account($id){
        $event = Event::findOrFail($id);
        $event_report = EventReport::where('event_id','=',$id)->get();
        if($event_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }

        if ($event->user) {
            $this->userService->blockUser($event->user);
        }

        // xác nhận trạng thái báo cáo đúng
        foreach ($event_report as $item){
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
        $event = Event::findOrFail($id);
        if ($event->user) {
            $this->userService->unblockUser($event->user);
        }

        Toastr::success("Bỏ chặn tài khoản thành công");
        return back();
    }
    // cấm tài khoản
    public function forbidden($id){
        $event = Event::findOrFail($id);
        $event_report = EventReport::where('event_id','=',$id)->get();
        if($event_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        if ($event->user) {
            $this->userService->forbiddenUser($event->user);
        }

        // xác nhận trạng thái báo cáo đúng
        foreach ($event_report as $item){
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

        // tìm sự kiện báo cáo
        $event = Event::findOrFail($id);
        if ($event->user) {
            $this->userService->unforbiddenUser($event->user);
        }
        Toastr::success("Bỏ cấm tài khoản thành công");
        return back();
    }
    // xóa tài khoản
    public function delete_account($id){
        // tìm các báo cáo
        $event = Event::findOrFail($id);
        $event_report = EventReport::where('event_id','=',$id)->get();
        if($event_report == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }
        if ($event->user) {
            $this->userService->deleteUser($event->user);
        }
        // xác nhận trạng thái báo cáo đúng
        foreach ($event_report as $item){
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

        // tìm sự kiện báo cáo
        $event = Event::findOrFail($id);
        if ($event->user) {
            $this->userService->restoreUser($event->user);
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
                $event_report = EventReport::where('event_id','=',$item)->get();
                if(!$event_report) continue;
                // Helper::create_admin_log(95,$data);

                foreach ($event_report as $i){
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
                $event_report = EventReport::where('event_id','=',$item)->get();
                if(!$event_report) continue;
                $event = Event::find($item);
                if(!$event) continue;

                $event->update([
                    'is_block' => 1
                ]);
                // Helper::create_admin_log(95,$data);

                // xác nhận trạng thái báo cáo đúng
                foreach ($event_report as $i){
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
                $event = Event::find($item);
                if(!$event) continue;
  
                $event->update([
                    'is_block' => 0
                ]);
                // Helper::create_admin_log(96,$data);
            }
            Toastr::success("Thành công");
            return back();
        }
        // Chặn tài khoản
        if($request->action_method == 'block_account'){
            foreach ($request->select_item as $item) {
                $event_report = EventReport::where('event_id','=',$item)->get();
                if (!$event_report) continue;
                $event = Event::find($item);
                if (!$event) continue;
                if ($event->user) {
                    $this->userService->blockUser($event->user);
                }
                // xác nhận trạng thái báo cáo đúng
                foreach ($event_report as $i){
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
                // tìm sự kiện báo cáo
                $event = Event::find($item);
                if (!$event) continue;
                if ($event->user) {
                    $this->userService->unblockUser($event->user);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Cấm tài khoản
        if($request->action_method == 'forbidden'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $event_report = EventReport::where('event_id','=',$item)->get();
                if (!$event_report) continue;
                // tìm sự kiện báo cáo
                $event = Event::find($item);
                if (!$event) continue;
                if ($event->user) {
                    $this->userService->forbiddenUser($event->user);
                }
                // xác nhận trạng thái báo cáo đúng
                foreach ($event_report as $i){
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
                // tìm sự kiện báo cáo
                $event = Event::find($item);
                if (!$event) continue;
                if ($event->user) {
                    $this->userService->unforbiddenUser($event->user);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Xóa tài khoản
        if($request->action_method == 'delete'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $event_report = EventReport::where('event_id','=',$item)->get();
                if (!$event_report) continue;
                // tìm sự kiện báo cáo
                $event = Event::find($item);
                if (!$event) continue;
                if ($event->user) {
                    $this->userService->deleteUser($event->user);
                }
                // xác nhận trạng thái báo cáo đúng
                foreach ($event_report as $i){
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
                // tìm sự kiện báo cáo
                $event = Event::find($item);
                if (!$event) continue;
                if ($event->user) {
                    $this->userService->restoreUser($event->user);
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
        // tìm sự kiện báo cáo
        $event = Event::find($request->id);
        if($event == null ){
            return response()->json('Không tồn tại sự kiện',500);
        }
        $data =[
            'object_type'=>0,
            'mail_title'=>$request->mail_title,
            'mail_content'=>$request->mail_content,
            'user_id'=>$event->user_id,
            'send_time'=>time(),
            'mailbox_type'=>'S',
            'created_by'=>Auth::guard('admin')->user()->id
        ];
        // Helper::create_admin_log(141,$data);
        MailBox::create($data);

        return response()->json('success',200);
    }
}
