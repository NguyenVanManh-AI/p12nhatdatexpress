<?php

namespace App\Http\Controllers\Admin\Report\Account;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\MailBox;
use App\Models\ReportGroup;
use App\Models\User;
use App\Models\User\UserPostReport;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\UserService;

class ReportAccountController extends Controller
{
    private UserService $userService;

    /**
     * inject UserService into UserController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function list_report_account(Request $request){
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        $list = User::Wherehas('report_account')
            ->where(function($query) use($request){
            $request->keyword!=''?$query->where('username','like','%'.$request->keyword.'%'):null;
            $request->keyword!=''?$query->orWhereRelation('user_detail','phone_number','like','%'.$request->keyword.'%'):null;
        })->where(function($query) use($request){
            // lọc theo nội dung báo cáo
           $request->report_content!=''?$query->WhereRelation('report_account','report_type','=',$request->report_content):null;
            // lọc theo ngày bắt đầu
           if($request->from_date!=''){
            $from =strtotime(Carbon::parse($request->from_date));
            $query->WhereRelation('report_account','report_time','>',$from);
          }
            // lọc theo ngày kết thúc
            if($request->to_date!=''){
                $to =strtotime(Carbon::parse($request->to_date)->addDay(1));
             $query->WhereRelation('report_account','report_time','<',$to);
            }
        });
        // phân trang
           $list =$list->paginate($items);
        // get report_group
        $report_group = ReportGroup::showed()->where('type','=',3)->get();
        return view('Admin.Report.Account.ReportAccount',compact('list','report_group'));
    }
    // báo cáo sai
    public function report_account_false($id){
        $account = UserPostReport::where('personal_id',$id)->where('report_position',3)->get();
        if($account == null){
            Toastr::error("Đã xảy ra lỗi");
            return back();
        }

        foreach ($account as $item){
            $item->update([
                'report_result' => 0,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }
        // $data =['id'=>$id];
        // Helper::create_admin_log(140,$data);

        Toastr::success("Cập nhật trạng thái thành công");
        return back();
    }
        //---------Chặn tài khoản
    public function block_account($id){
        $user = User::findOrFail($id);

        $this->userService->blockUser($user);

        $report = UserPostReport::where('personal_id',$id)->where('report_position',3)->get();
        foreach ($report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }

        Toastr::success('Chặn tài khoản thành công');
        return back();
    }
    //---------Bỏ chặn tài khoản
    public function unblock_account($id){
        $user = User::findOrFail($id);;
        $this->userService->unblockUser($user);

        Toastr::success('Bỏ chặn tài khoản thành công');
        return back();
    }
    // --------- Cấm tài khoản
    public function forbidden_account($id){
        $user = User::findOrFail($id);;
        $this->userService->forbiddenUser($user);

        $report = UserPostReport::where('personal_id',$id)->where('report_position',3)->get();
        foreach ($report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }

        Toastr::success('Cấm tài khoản thành công');
        return back();
    }
    // --------- Bỏ cấm tài khoản
    public function unforbidden_account($id){
        $user = User::findOrFail($id);;
        $this->userService->unforbiddenUser($user);

        Toastr::success('Bỏ cấm tài khoản thành công');
        return back();
    }
    //---------Xóa tài khoản
    public function delete_account($id){
        $user = User::findOrFail($id);;
        $this->userService->deleteUser($user);

        $report = UserPostReport::where('personal_id',$id)->where('report_position',3)->get();
        foreach ($report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }

        Toastr::success('Xóa tài khoản thành công');
        return back();
    }
    //---------Khôi phục tài khoản
    public function undelete_account($id){
        $user = User::findOrFail($id);;
        $this->userService->restoreUser($user);

        Toastr::success('Khôi phục tài khoản thành công');
        return back();
    }
      // ----------
    public function list_action(Request $request){
    if ($request->select_item == null) {
        Toastr::warning("Vui lòng chọn");
        return back();
    }
        // báo cáo sai
        if($request->action_method == 'report_false'){
            foreach ($request->select_item as $item) {
                // $data =['id'=>$item];
                // Helper::create_admin_log(140,$data);
                $account = UserPostReport::where('personal_id',$item)->where('report_position',3)->get();
                if($account == null){
                    Toastr::error("Đã xảy ra lỗi");
                    return back();
                }
                foreach ($account as $i){
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
        // Chặn tài khoản
        if($request->action_method == 'block_account'){
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;
                $this->userService->blockUser($user);

                $report = UserPostReport::where('personal_id',$item)->where('report_position',3)->get();
                foreach ($report as $i){
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
                $user = User::find($item);
                if (!$user) continue;
                $this->userService->unblockUser($user);
            }
            Toastr::success("Thành công");
            return back();
        }
        // Cấm tài khoản
        if($request->action_method == 'forbidden'){
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;
                $this->userService->forbiddenUser($user);

                $report = UserPostReport::where('personal_id',$item)->where('report_position',3)->get();
                foreach ($report as $i){
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
                $user = User::find($item);
                if (!$user) continue;
                $this->userService->unforbiddenUser($user);
            }
            Toastr::success("Thành công");
            return back();
        }
        // Xóa tài khoản
        if($request->action_method == 'delete'){
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;
                $this->userService->deleteUser($user);

                $report = UserPostReport::where('personal_id',$item)->where('report_position',3)->get();
                foreach ($report as $i){
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
        if($request->action_method == 'undelete'){
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;
                $this->userService->restoreUser($user);
            }
            Toastr::success("Thành công");
            return back();
        }
    }
    public function create_notification(Request $request){
        if($request->mail_title == '' ||$request->mail_content ==''){
            return response()->json('Vui lòng nhập đầy đủ các trường',500);
        }
        $data =[
            'object_type'=>0,
            'mail_title'=>$request->mail_title,
            'mail_content'=>$request->mail_content,
            'user_id'=>$request->id,
            'send_time'=>time(),
            'mailbox_type'=>'S',
            'created_by'=>Auth::guard('admin')->user()->id
        ];

        // Helper::create_admin_log(141,$data);
        MailBox::create($data);
        return response()->json('success',200);
    }
}
