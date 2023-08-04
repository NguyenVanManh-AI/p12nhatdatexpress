<?php

namespace App\Http\Controllers\Admin\SystemConfig;

use App\CPU\HelperImage;
use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use App\Models\AdminMailConfig;
use App\Models\SystemConfig;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ConfigController extends Controller
{
    private $email_code = "C018";
    private $percent_affiliate_code = "C019";

    public function systemConfig()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->admin_type != 1) {
            $listPermissions = unserialize(data_get(session()->get('role'), 'role_content')) ?? null;
            $canAddMailConfig = data_get($listPermissions, '37.1.check');
        } else {
            $canAddMailConfig = true;
        }

        $system = SystemConfig::first();
        $is_email_campaign = AdminConfig::where('config_code', $this->email_code)->first();
        $percent_affiliate = AdminConfig::where('config_code', $this->percent_affiliate_code)->first();
        $mail = AdminMailConfig::where('is_config', 1)->get();

        return view('Admin.SystemConfig.General',compact('system','mail', 'is_email_campaign', 'percent_affiliate', 'canAddMailConfig'));
    }

    public function updateSystemConfig(Request $request)
    {
            $request->validate([
                'facebook'=>'required|url|regex:/http(?:s):\/\/(?:www\.)(facebook\.com)\/?.+/i|max:255',
                'youtube'=>'required|url|regex:/http(?:s):\/\/(?:www\.)(youtube\.com)\/?.+/i',
                'linkedlin'=>'required|url|regex:/http(?:s):\/\/(?:www\.)(linkedin\.com)\/?.+/i',
                'mail_notification'=>'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                'mail_deposit'=>['required','regex:/^([A-Za-z0-9\.|-|_]*[@]{1}[A-Za-z0-9\.|-|_]*[.]{1}[a-z]{2,5})(,[A-Za-z0-9\.|-|_]*[@]{1}[A-Za-z0-9\.|-|_]*[.]{1}[a-z]{2,5})*?$/'],
                'mail_express'=>['required','regex:/^([A-Za-z0-9\.|-|_]*[@]{1}[A-Za-z0-9\.|-|_]*[.]{1}[a-z]{2,5})(,[A-Za-z0-9\.|-|_]*[@]{1}[A-Za-z0-9\.|-|_]*[.]{1}[a-z]{2,5})*?$/'],
                'google_map'=>'required',
                'post_fake'=>'required',
                'header'=>'',
                'body'=>'',
                'footer'=>'',
                'logo1'=>'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                'logo2'=>'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                'logo3'=>'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                'logo4'=>'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                'banner'=>'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                'introduce'=>'',
                'apple_store'=>'',
                'ch_play'=>'',
                'text_footer'=>'',
                'info_company'=>'',
            ],[
                //logo1
                'logo1.required'=>'* Vui lòng chọn ảnh',
                'logo1.image'=>'* Chỉ được tải lên hình ảnh',
                'logo1.mimes'=>'* Chỉ được tải lên hình ảnh cho phép',
                'logo2.required'=>'* Vui lòng chọn ảnh',
                'logo2.image'=>'* Chỉ được tải lên hình ảnh',
                'logo2.mimes'=>'* Chỉ được tải lên hình ảnh cho phép',
                'logo3.required'=>'* Vui lòng chọn ảnh',
                'logo3.image'=>'* Chỉ được tải lên hình ảnh',
                'logo3.mimes'=>'* Chỉ được tải lên hình ảnh cho phép',
                'logo4.required'=>'* Vui lòng chọn ảnh',
                'logo4.image'=>'* Chỉ được tải lên hình ảnh',
                'logo4.mimes'=>'* Chỉ được tải lên hình ảnh cho phép',
                'banner.required'=>'* Vui lòng chọn ảnh',
                'banner.image'=>'* Chỉ được tải lên hình ảnh',
                'banner.mimes'=>'* Chỉ được tải lên hình ảnh cho phép',
                //facebook
                'facebook.required'=>'* Vui lòng nhập link facebook',
                'facebook.url'=>'* Đường dẫn không hợp lệ',
                'facebook.regex'=>'* Đường dẫn không hợp lệ',
                'facebook.max'=>'* Độ dài đường dẫn cho phép từ 1 - 255 kí tự',
                // youtube
                'youtube.required'=>'* Vui lòng nhập link youtube',
                'youtube.url'=>'* Đường dẫn không hợp lệ',
                'youtube.regex'=>'* Đường dẫn không hợp lệ',

                //linked
                'linkedlin.required'=>'* Vui lòng nhập link linkedin',
                'linkedlin.url'=>'* Đường dẫn không hợp lệ',
                'linkedlin.regex'=>'* Đường dẫn không hợp lệ',
                //mail noti
                'mail_notification.required'=>'* Vui lòng nhập trường này',
                'mail_notification.email'=>'* Định dạng email không đúng',
                'mail_notification.max'=>'* Email không được vượt 255 kí tự',
                'mail_notification.regex'=>'* Định dạng email không đúng',
                //mail deposit
                'mail_deposit.required'=>'* Vui lòng nhập trường này',
                'mail_deposit.regex'=>'* Danh sách email không hợp lệ',
                'mail_express.required'=>'* Vui lòng nhập trường này',
                'mail_express.regex'=>'* Danh sách email không hợp lệ',

                'google_map.required'=>'* Vui lòng nhập trường này',
                'header.required'=>'* Vui lòng nhập trường này',
                'body.required'=>'* Vui lòng nhập trường này',
                'footer.required'=>'* Vui lòng nhập trường này',
                'apple_store.required'=>'* Vui lòng nhập trường này',
                'ch_play.required'=>'* Vui lòng nhập trường này',
                'introduce.required'=>'* Vui lòng nhập trường này',
                'text_footer.required'=>'* Vui lòng nhập trường này',
                'info_company.required'=>'* Vui lòng nhập trường này',
            ]);
            // xóa khoảng trắng trong chuỗi các email
         $request->mail_deposit = str_replace(' ','', $request->mail_deposit);
         $request->mail_express = str_replace(' ','', $request->mail_express);

         // chuyển thành mảng
        $mail_deposit = explode(',', $request->mail_deposit);
        $mail_express = explode(',',$request->mail_express);

        // lấy data từ request
        $data = [
            'facebook'=>$request->facebook,
            'youtube'=>$request->youtube,
            'linkedlin'=>$request->linkedlin,
            'mail_notification'=>$request->mail_notification,
            'mail_deposit'=>implode(',',$mail_deposit),
            'mail_express'=>implode(',',$mail_express),
            'google_map'=>$request->google_map,
            'post_fake'=>$request->post_fake,
            'header'=>$request->header,
            'body'=>$request->body,
            'footer'=>$request->footer,
//            'logo1'=>'logo1',
//            'logo2'=>'logo2',
//            'logo3'=>'logo3',
            'introduce'=>$request->introduce,
            'apple_store'=>$request->apple_store,
            'ch_play'=>$request->ch_play,
            'text_footer'=>$request->text_footer,
            'info_company'=>$request->info_company,

        ];

        // forget system_config cache
        Cache::forget('system_config');
        $system = SystemConfig::firstOrCreate();

        $fileUploads = [
            'logo1', 'logo2', 'logo3', 'logo4', 'banner'
        ];

        foreach ($fileUploads as $fileUpload) {
            if ($request->hasFile($fileUpload)) {
                // change image
                $folder = 'system/img/logo';

                $fileName = HelperImage::updateImage($folder, $request->file($fileUpload), data_get($system, $fileUpload));
                $data[$fileUpload] = $folder . '/' . $fileName;
            } else if (data_get($request, "old_$fileUpload") == null && data_get($system, $fileUpload)) {
                // only remove existing image
                HelperImage::removeOldImage(data_get($system, $fileUpload));
                $data[$fileUpload] = null;
            }
        }

        $system->update($data);

        // Update is email campaign
        $code = $this->email_code;
        Cache::forget('mail_campaign_config');
        AdminConfig::where('config_code', $this->email_code)->update([
           'config_value' => $request->$code ?? 0
        ]);
        $data['is_campaign'] = $request->$code ?? 0;

        // Update is percent affiliate
        $code = $this->percent_affiliate_code;
        AdminConfig::where('config_code', $this->percent_affiliate_code)->update([
           'config_value' => $request->$code ?? 0
        ]);
        $data['percent_affiliate'] = $request->$code ?? 0;

        # Note log
        // Helper::create_admin_log(1, $data);

        Toastr::success('Thành công');
        return back();
    }
    public function test_mail(Request $request){
        $validate = $request->validate([
           'smtp'=>'required',
           'account'=>'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
           'portsmtp'=>'required',
           'password'=>'required',

        ],[
            'smtp.required'=>'Vui lòng nhập trường này',
            'account.required'=>'Vui lòng nhập trường này',
            'account.email'=>'Định dạng email không hợp lệ',
            'account.regex'=>'Định dạng email không hợp lệ',
            'portsmtp.required'=>'Vui lòng nhập trường này',
            'password.required'=>'Vui lòng nhập trường này',
        ]);
        try{
            $transport = (new \Swift_SmtpTransport($request->smtp,$request->portsmtp))
                ->setUsername($request->account)->setPassword($request->password)->setEncryption('tls');

            $mailer = new \Swift_Mailer($transport);
            $message = (new \Swift_Message('Test mail'))
                ->setFrom([$request->account => 'Account Test'])
                ->setTo([$request->account,$request->account => 'Name test'])
                ->setBody('Test email');
            $result = $mailer->send($message);
        }
        catch (\Swift_TransportException $transportExp){
            Toastr::warning("Thất bại");
            return back();
        }

            Toastr::success("Cập nhật thành công");


              return back()->with(['account'=>$request->account,'password'=>$request->password]);
    }
    public function add_mail(Request $request){
        $validate = $request->validate([
            'smtp'=>'required',
            'account'=>'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'portsmtp'=>'required',
            'password'=>'required',

        ],[
            'smtp.required'=>'Vui lòng nhập trường này',
            'account.required'=>'Vui lòng nhập trường này',
            'account.email'=>'Định dạng email không hợp lệ',
            'account.regex'=>'Định dạng email không hợp lệ',
            'portsmtp.required'=>'Vui lòng nhập trường này',
            'password.required'=>'Vui lòng nhập trường này',
        ]);
        $data =[
            'mail_host'=>$request->smtp,
            'mail_port'=>$request->portsmtp,
            'mail_username'=>$request->account,
            'mail_password'=>$request->password,
            'mail_encryption'=>'tls',
        ];

        AdminMailConfig::create($data);

        # Note log
        // Helper::create_admin_log(1, "Tạo mới cấu hình email gửi");

        Toastr::success('Thành công');
        response()->json(['success'=>'Ajax request submitted successfully'],200);
    }


    public function postedit_mail(Request $request,$id){
        $validate = $request->validate([
            'smtp'=>'required',
            'account'=>'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'portsmtp'=>'required',
            'password'=>'required',

        ],[
            'smtp.required'=>'Vui lòng nhập trường này',
            'account.required'=>'Vui lòng nhập trường này',
            'account.email'=>'Vui lòng nhập mail hợp lệ',
            'account.regex'=>'Vui lòng nhập mail hợp lệ',
            'portsmtp.required'=>'Vui lòng nhập trường này',
            'password.required'=>'Vui lòng nhập trường này',
        ]);
        $data =[
            'mail_host'=>$request->smtp,
            'mail_port'=>$request->portsmtp,
            'mail_username'=>$request->account,
            'mail_password'=>$request->password,
            'mail_encryption'=>'tls',
        ];

        $config = AdminMailConfig::findOrFail($id);
        $config->update($data);

        # Note log
        // Helper::create_admin_log(1, "Cập nhật cấu hình email gửi");

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.system.general'));
    }

    public function delete_mail($id){
        AdminMailConfig::where('id',$id)->delete();

        # Note log
        // Helper::create_admin_log(1, "Xóa cấu hình email gửi");

        Toastr::success('Thành công');
        return back();
    }
    public function edit_mail($id){
        $admin_mail = AdminMailConfig::where('id',$id)->first();
        return view('Admin.SystemConfig.UpdateMailAdmin',compact('admin_mail'));
    }
}
