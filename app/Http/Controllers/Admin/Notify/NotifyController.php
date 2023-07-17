<?php

namespace App\Http\Controllers\Admin\Notify;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notify\CreateNotifyRequest;
use App\Models\MailBox;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotifyController extends Controller
{
    # Tạo thông báo
    public function make_notify(CreateNotifyRequest $request)
    {
        MailBox::create([
            'object_type' => 0,
            'parent_id' => null,
            'mail_title' => $request->mail_title,
            'mail_content' => $request->mail_content,
            'user_id' => $request->user_id,
            'mailbox_type' => $request->mailbox_type,
            'send_time' => time(),
            'created_by' => Auth::guard('admin')->id()
        ]);

        // $data =[
        //     'object_type' => 0,
        //     'parent_id' => null,
        //     'mail_title' => $request->mail_title,
        //     'mail_content' => $request->mail_content,
        //     'user_id' => $request->user_id,
        //     'mailbox_type' => $request->mailbox_type,
        //     'send_time' => time(),
        //     'created_by' => Auth::guard('admin')->id()
        // ];
        // Helper::create_admin_log(114,$data);

        Toastr::success('Tạo thông báo thành công');
        return redirect()->back();
    }
}
