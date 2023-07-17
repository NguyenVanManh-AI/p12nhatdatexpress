<?php

namespace App\Http\Controllers\Admin\MailBox;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\MailBox;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MailBoxController extends Controller
{

    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
//        $list = DB::table('maibox')->select('id' ,'object_type')->where('parent_id')->get('items');
        $getListMailBox = MailBox::query()
        ->orderBy('send_time', 'desc')
        ->where('mailbox.parent_id','=', null);
            // Tìm kiếm
        if ($request->search) {
                // dd($request->search);
            $getListMailBox->where('mailbox.mail_title', 'like', '%' . $request->search . '%');
        }
        $getListMailBox= $getListMailBox->paginate($items);
        return view('Admin.MailBox.ListMail',['list'=>$getListMailBox]);


    }
    public function trash(Request $request){
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $getListMailBox = MailBox::onlyIsDeleted()
        ->latest('send_time');
     // Tìm kiếm
        if ($request->search) {
            $getListMailBox->where('mailbox.mail_title', 'like', '%' . $request->search . '%');
        }

        $getListMailBox= $getListMailBox->paginate($items);
        return view('Admin.MailBox.TrashMail',['list'=>$getListMailBox]);


    }

    public function trash_list(Request $request)
    {
        // dd($request->all());
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $mailBox = MailBox::find($item);
            if (!$mailBox) continue;

            $mailBox->delete();
            // Helper::create_admin_log(125,$data);
        }

        Toastr::success(' Xóa thành công');
        return back();

    }

    public function untrash_list(Request $request)
    {
        // dd($request->check);
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $mailBox = MailBox::onlyIsDeleted()->find($item);
            if (!$mailBox) continue;

            $mailBox->restore();
            // Helper::create_admin_log(126,$data);
        }

        Toastr::success('Khôi phục thành công');
        return back();

    }

    // public function forceDeleteMultiple(Request $request)
    // {
    //     $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

    //     MailBox::onlyIsDeleted()->find($ids)
    //         ->each(function($item) {
    //             $item->forceDelete();

    //             // should create log force delete
    //         });

    //     Toastr::success('Xóa thành công');
    // }

    // phân loại mail đã đọc || chưa đọc
    public function read_mail(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $list = MailBox::query()
        ->where('mailbox_status', 1)
        ->orderBy('send_time','desc')
        ->where('mailbox.object_type','=',1);
         // Tìm kiếm
        if ($request->search) {
            $list->where('mailbox.mail_title', 'like', '%' . $request->search . '%');
        }
        $list = $list->paginate($items);
        return view('Admin.MailBox.ReadMail', compact('list'));


    }

    // chỉ những mail chưa đọc
    public function unread_mail(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $list = MailBox::query()
        ->where('mailbox_status', 0)
        ->orderBy('send_time','desc')
        ->where('mailbox.object_type','=',1);
        if ($request->search) {
            $list->where('mailbox.mail_title', 'like', '%' . $request->search . '%');
        }

        $list = $list->paginate($items);

    return view('Admin.MailBox.UnReadMail', compact('list'));
    }

//Cập nhật trạng thái Readmail và Unreadmail
    public function updateStatus(Request $request)
    {

        MailBox::where('id', $request->id)->update(['mailbox_status' => "1"]);

        return response()->json(['message' => 'User status updated successfully.']);
    }

    public function unupdateStatus(Request $request)
    {

        MailBox::where('id', $request->id)->update(['mailbox_status' => "0"]);

        return response()->json(['message' => 'User status updated successfully.']);

    }

    // trạng thái ghim or unghim
    public function pin_mail(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $list = MailBox::query()
        ->where('mailbox_pin', 1)
        ->latest('send_time');
        if ($request->search) {
            $list->where('mailbox.mail_title', 'like', '%' . $request->search . '%');
        }
        $list = $list->paginate($items);

        MailBox::where('mailbox_pin', $request->mailbox_pin)->update(['mailbox_pin' => "1"]);
        return view('Admin.MailBox.PinMail', compact('list'));
//                return response()->json(['message' => 'Ghim mail thành công .']);
    }
    public function pin($id)
    {
        $mail = MailBox::find($id);
        if($mail ==null){
            return response()->json(['message'=>'Không tồn tại'],404);
        }
        if($mail->mailbox_pin == 1 ){
            MailBox::where('id', $id)->update(['mailbox_pin' => "0"]);
            return response()->json(['message' => 'Ghim mail thành công .'],200);
        }
        else{
            MailBox::where('id', $id)->update(['mailbox_pin' => "1"]);
            return response()->json(['message' => 'Ghim mail thành công .'],200);
        }

    }

    public function unpin(Request $request)
    {
        MailBox::where('id', $request->id)->update(['mailbox_pin' => "0"]);

        return response()->json(['message' => 'Bỏ ghim mail thành công .']);
    }

    // nofitication tin đăng
    public function nofitication_post(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $list = MailBox::query()
        ->where('mailbox_type', 'I')
        ->latest('send_time');
        if ($request->search) {
            $list->where('mailbox.mail_title', 'like', '%' . $request->search . '%');
        }
        $list = $list->paginate($items);
        return view('Admin.MailBox.NotificationMail', compact('list'));

    }

    // nofitication tài khoản
    public function nofitication_acc(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $list = MailBox::query()
        ->where('mailbox_type', 'A')
        ->latest('send_time');
        if ($request->search) {
            $list->where('mailbox.mail_title', 'like', '%' . $request->search . '%');
        }
        $list = $list->paginate($items);
        return view('Admin.MailBox.NotificationAcc', compact('list'));

    }


    // nofitication hệ thống
    public function nofitication_system(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $list = MailBox::query()
        ->where('mailbox_type', 'S')
        ->latest('send_time');
        if ($request->search) {

            $list->where('mailbox.mail_title', 'like', '%' . $request->search . '%');
        }
        $list = $list->paginate($items);
        return view('Admin.MailBox.NotificationSystem', compact('list'));
    }
    // chi tiết mail
    public function detail_mail($id){
        $detail = MailBox::findOrFail($id);
        $childDetail = MailBox::query()
        ->where('parent_id',$id)
        ->get();
        $user = User::get();
        $admin = Admin::get();
        $detail->update([
            'mailbox_status'=>1
        ]);

        return view('Admin.MailBox.DetailMail', compact('detail','childDetail','user','admin'));
    }

    //trả lời mail
    public function reply($id,Request $request){
        //validate nội dung trả lời
        $validate = $request->validate([
            'reply' => 'required|max:25000',
        ]);
        //lấy ra mail được trả lời
        $getMail = MailBox::findOrFail($id);

        MailBox::create([
            'object_type' => 0,
            'parent_id' => $id,
            'mail_title' => $getMail->mail_title,
            'mail_content' => $request->reply,
            'user_id'=> $getMail->user_id ?: $getMail->created_byuser_id,
            'send_time' => time(),
            'mailbox_type' => $getMail->mailbox_type,
            'created_by' => Auth::guard('admin')->user()->id
        ]);
        // Helper::create_admin_log(127,$data);

        Toastr::success('Trả lời thành công');
        return back();
    }
    //giao diện gửi mail
    public function add(){
        //lấy ra list user
        $list_users = User::get();
        //lấy ra view gửi mail
        return view('Admin.MailBox.AddMail',['list_users'=>$list_users]);
    }
    //gửi thư cho nhiều user
    public function post_add(Request $request){
        //validate các giá trị
        $validate = $request->validate([
            'list_users' => 'required',
            'title' => 'required|max:255',
            'content' => 'required|max:25000',
        ]);
        //lặp tất cả user được chọn
        foreach ($request->list_users as $key => $value) {
            //insert vào bảng mailbox
            MailBox::create([
                'object_type'=>0,
                'parent_id'=>null,
                'mail_title'=>$request->title,
                'mail_content'=>$request->content,
                'user_id'=>$value,
                'send_time'=>time(),
                'created_by'=>Auth::guard('admin')->user()->id
            ]);
            // $data =[
            //     'object_type'=>0,
            //     'parent_id'=>null,
            //     'mail_title'=>$request->title,
            //     'mail_content'=>$request->content,
            //     'user_id'=>$value,
            //     'send_time'=>time(),
            //     'created_by'=>Auth::guard('admin')->user()->id
            // ];
            // Helper::create_admin_log(128,$data);
        }
        //thông báo thành công
        Toastr::success('Gửi thư thành công');
        //chuyển hướng đến trang danh sách mail
        return redirect()->route('admin.mailbox.list');
    }
    //gửi mail get nhận giá trị user_id, title, content
    public function post_add_mail(Request $request){
        //validate các giá trị
        $validate = $request->validate([
            'user_id' => 'required',
            'title' => 'required|max:255',
            'content' => 'required|max:25000',
        ]);
        //insert vào bảng mailbox
        MailBox::create([
            'object_type'=>0,
            'parent_id'=>null,
            'mail_title'=>$request->title,
            'mail_content'=>$request->content,
            'user_id'=>$request->user_id,
            'send_time'=>time(),
            'created_by'=>Auth::guard('admin')->user()->id
        ]);
        // $data =[
        //     'object_type'=>0,
        //     'parent_id'=>null,
        //     'mail_title'=>$request->title,
        //     'mail_content'=>$request->content,
        //     'user_id'=>$request->user_id,
        //     'send_time'=>time(),
        //     'created_by'=>Auth::guard('admin')->user()->id
        // ];
        // Helper::create_admin_log(128,$data);
        //thông báo thành công
        Toastr::success('Gửi thư thành công');
        //trở về trang trước
        return back();
    }
}
