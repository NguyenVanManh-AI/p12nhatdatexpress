<?php

namespace App\Http\Controllers\Admin\Event;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventReport;
use App\Models\ReportGroup;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EventReportController extends Controller
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
        $getReportReason = ReportGroup::where('type', 1)->orderby('id', 'desc')->get();

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

        return view('Admin.Event.ListReportEvent', compact('list', 'getReportReason'));
    }


    /**
     * Chặn hiển thị
     * @param $id
     * @return RedirectResponse
     */
    public function block_display($id)
    {
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

    /**
     * Khôi phục hiển thị
     * @param $id
     * @return RedirectResponse
     */
    public function unblock_display($id)
    {
        $event = Event::findOrFail($id);
        $event->update([
            'is_block' => 0
        ]);
        // Helper::create_admin_log(96,$data);

        Toastr::success("Khôi phục hiển thị thành công");
        return back();
    }

    /**
     * Chặn tài khoản
     * @param $id
     * @return RedirectResponse
     */
    public function locked($id)
    {
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

    /**
     * Cấm tài khoản
     * @param $id
     * @return RedirectResponse
     */
    public function forbidden($id)
    {
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

    /**
     * Xóa tài khoản
     * @param $id
     * @return RedirectResponse
     */
    public function delete_user($id)
    {
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

    /**
     * Thao tác hàng loạt
     * @param Request $request
     * @return RedirectResponse|void
     */
    public function list_action(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        // Chặn hiển thị
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
                if(!$event_report) continue;
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
        // Cấm tài khoản
        if($request->action_method == 'forbidden'){
            foreach ($request->select_item as $item) {
                $event_report = EventReport::where('event_id','=',$item)->get();
                if(!$event_report) continue;
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
        // Xóa tài khoản
        if($request->action_method == 'delete'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $event_report = EventReport::where('event_id','=',$item)->get();
                if(!$event_report) continue;
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
    }
}
