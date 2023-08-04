<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Mail\AddMailRequest;
use App\Http\Requests\User\Mail\AddTemplateMailRequest;
use App\Http\Requests\User\Mail\EditMailRequest;
use App\Http\Requests\User\Mail\EditTemplateMailRequest;
use App\Http\Requests\User\MailConfigTestRequest;
use App\Jobs\SendUserEmail;
use App\Models\AdminConfig;
use App\Models\UserMailConfig;
use App\Models\UserMailTemplate;
use App\Services\MailService;
use App\Services\UserMailTemplateService;
use App\Services\Users\MailConfigService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MailController extends Controller
{
    private MailConfigService $mailConfigService;
    private MailService $mailService;
    private UserMailTemplateService $userMailTemplateService;
    public $mailConfigGuide;

    public function __construct()
    {
        $this->mailConfigService = new MailConfigService;
        $this->mailService = new MailService;
        $this->userMailTemplateService = new UserMailTemplateService;
        $this->mailConfigGuide = AdminConfig::firstWhere('config_code', 'N015');
    }

    #Cấu hình mail
    public function config_mail()
    {
        $user = Auth::guard('user')->user();
        $userMailConfigs = $user->mailConfigs()
            ->select('id', 'mail_username', 'num_mail')
            ->latest('created_at')
            ->get();

        return view('user.mail.configs.add', [
            'userMailConfigs' => $userMailConfigs,
            'mailConfig' => new UserMailConfig(),
            'guide' => $this->mailConfigGuide
        ]);
    }

    #Tạo cấu hình mail
    public function post_config_mail(AddMailRequest $request)
    {
        $user = Auth::guard('user')->user();

        // maybe should check send test mail here
        $mailcount = DB::table('user_mail_config')->where('user_id', $user->id)->count();
        if ($mailcount > 10) {
            Toastr::error('Số mail cấu hình không lớn hơn 10!');
            return redirect()->back();
        }

        $this->mailConfigService->create($user, $request->all());

        Toastr::success('Tạo cấu hình mail thành công');
        return redirect()->back();
    }

    #cap nhat cau hinh mail
    public function edit_config_mail($id)
    {
        $user = Auth::guard('user')->user();

        $mailConfig = $user->mailConfigs()
            ->findOrFail($id);

        return view('user.mail.configs.edit', [
            'mailConfig' => $mailConfig,
            'guide' => $this->mailConfigGuide
        ]);
    }

    #post cap nhat cau hinh mail
    public function post_edit_config_mail(EditMailRequest $request, $id)
    {
        $user = Auth::guard('user')->user();

        $mailConfig = $user->mailConfigs()
            ->findOrFail($id);

        $this->mailConfigService->update($mailConfig, $user, $request->all());

        Toastr::success('Cập nhật cấu hình mail thành công');

        return redirect()->back();

    }


    #xoa cau hinh mail
    public function delete_config_mail($id)
    {
        $user = Auth::guard('user')->user();
        DB::table('user_mail_config')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->update(['is_deleted' => 1]);

        Toastr::success('Xóa cấu hình mail thành công');
        return redirect()->back();

    }

    #Tạo mẫu mail
    public function template_mail()
    {
        $user = Auth::guard('user')->user();
        $params['temlate_mail'] = UserMailTemplate::select('id', 'mail_header', 'mail_content')
            ->where('user_id', $user->id)
            ->get();

        $params['guide'] = DB::table('admin_config')->where('config_code', 'N009')->first();

        $params['template'] = new UserMailTemplate();

        return view('user.mail.template.add', $params);
    }

    #Thêm mẫu mail
    public function add_template_mail(AddTemplateMailRequest $request)
    {
        $user = Auth::guard('user')->user();

        // maybe without deleted
        $template_number = DB::table('user_mail_template')->where('user_id', $user->id)->count();
        if ($template_number >= 10)
        {
            Toastr::error('Chỉ được tạo tối đa 10 mẫu mail');
            return redirect()->back();
        }

        $this->userMailTemplateService->create($user, $request->all());

        Toastr::success('Tạo mẫu mail thành công');
        return redirect()->back();
    }

    #Cập nhật mail template
    public function edit_template_mail($template_mail_id)
    {
        $user = Auth::guard('user')->user();

        $params['template'] = DB::table('user_mail_template')
            ->select('id', 'mail_header', 'mail_content')
            ->where('user_id', $user->id)
            ->where('id', $template_mail_id)
            ->first();

        $params['guide'] = DB::table('admin_config')->where('config_code', 'N009')->first();

        if (! $params['template']) {
            Toastr::error('Không tồn tại mẫu mail');
            return redirect()->back();
        }

        $params['temlate_mail'] = UserMailTemplate::select('id', 'mail_header', 'mail_content')
            ->where('user_id', $user->id)
            ->get();

        return view('user.mail.template.edit', $params);
    }

    #post mau mail
    public function post_edit_template_mail($id, EditTemplateMailRequest $request)
    {
        $user = Auth::guard('user')->user();

        $mail = UserMailTemplate::findOrFail($id);
        $this->userMailTemplateService->update($mail, $user, $request->all());

        Toastr::success('Cập nhật mẫu mail thành công');
        return redirect()->back();
    }

    #Xóa mail template
    public function delete_template_mail($template_mail_id)
    {
        $user = Auth::guard('user')->user();
        DB::table('user_mail_template')
            ->where('user_id', $user->id)
            ->where('id', $template_mail_id)
            ->update(['is_deleted' => 1]);

        Toastr::success('Xóa mẫu mail thành công');
        return redirect()->back();
    }

    public function mailConfigTest(MailConfigTestRequest $request)
    {
        $host = $request->mail_host;
        $port = $request->mail_port;
        $encryption = $request->mail_encription;
        $userName = $request->mail_username;
        $password = $request->mail_password;
        $sentToMail = $request->user_mail;

        try{
            $this->mailService->sendMailTest(
                $host,
                $port,
                $encryption,
                $userName,
                $password,
                $sentToMail,
                'Test Email marketing Nội dung',
                'Test thành công, Bạn có thể lưu mail này để sử dụng'
            );

            return response()->json([
                'success' => true,
                'message' => 'Gửi thử thành công! Bạn có thể thêm email này'
            ], 200);
		} catch (\Swift_TransportException $e) {
            // Log::debug($e);
            return response()->json([
                'success' => false,
                'message' => 'Gửi thử thất bại! Vui lòng kiểm tra lại thông tin cấu hình'
            ], 422);
		}
    }
}
