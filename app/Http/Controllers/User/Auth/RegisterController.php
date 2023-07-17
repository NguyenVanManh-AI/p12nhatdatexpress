<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendUserEmail;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\Auth\RegisterRequest;
use Illuminate\Support\Facades\DB;
use App\CPU\InitUserAccount;


/**
 * User register controller
 */
class RegisterController extends Controller
{

    /**
     * User register account
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function post_register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            do {
                $usercode = rand(1000000000, 9999999999);
            } while (User::where('user_code', $usercode)->first());

            $userData = [
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'user_code' => $usercode,
                'user_type_id' => $request->user_type,
                'phone_number' => $request->phone_number,
                'phone_securiry' => 0,
                'email' => $request->email,
                'user_ref_id' => session()->get('user_ref_id'),
                'login_method' => 0,
                'is_confirmed' => $request->user_type == 3 ? 0 : 1,
                'created_at' => time()
            ];
            $user = User::create($userData);

            $userLocation = [
                'user_id' => $user->id,
                'province_id' => $request->province,
                'district_id' => $request->district,
                'ward_id' => $request->ward
            ];
            DB::table('user_location')->insert($userLocation);

            $userDetail = [
                'user_id' => $user->id,
                'fullname' => $request->fullname,
                'tax_number' => $request->tax_number,
                'source_from' => $request->source,
                'birthday' => $request->user_type != 3 ? strtotime($request->birthday) : null,
                'website' => $request->website
            ];

            if ($request->user_type == 3) {
                $upload_dir = "uploads/users/$usercode";
                $businessLicense = file_helper($request->file('upload-license'), $upload_dir);
                $userDetail['business_license'] = $businessLicense;
            }
            DB::table('user_detail')->insert($userDetail);
            DB::commit();
            InitUserAccount::init($user);
            session()->forget('user_ref_id');
            if ($request->user_type == 3)  {
                Toastr::success('Đăng ký tài khoản thành công, tài khoản sẽ được xét duyệt trong vòng 24h!');
            }
            else {
                Toastr::success('Đăng ký tài khoản thành công, vui lòng kiểm tra email để kích hoạt tài khoản!');
                $this->sendActiveMail($user);
            }


        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error('Đăng ký không thành công, vui lòng liên hệ Admin!');

        } finally {
            return redirect()->back();
        }

    }


    /**
     * System send active account link by email
     * @param User $user
     * @return void
     */
    public function sendActiveMail(User $user)
    {

        $mailTemplate = DB::table('admin_mail_template')->where('template_action', 'kich-hoat-tai-khoan')->first();
        $mailConfig = DB::table('admin_mail_config')->where('is_deleted', 0)->first();
        if ($mailTemplate && $mailConfig) {
            $active_link = route('user.active-account', encrypt($user->user_code));
            $title = $mailTemplate->template_title;
            $message = str_replace('%link%', $active_link, $mailTemplate->template_content);
            $sendUserEmailJob = new SendUserEmail($mailConfig->mail_host, $mailConfig->mail_port, $mailConfig->mail_encryption, $mailConfig->mail_username, $mailConfig->mail_password, $user->email, $mailConfig, $title, $message);
            dispatch($sendUserEmailJob);
        }
    }

    /**
     * New user active account by link was sent by system's email
     * @param $encryptString
     * @return \Illuminate\Http\RedirectResponse
     */
    public function get_active($encryptString)
    {
        // should change to generate one time token
        $userCode = decrypt($encryptString);
        $user = User::where('user_code', $userCode)
            ->where('is_deleted', 0)
            ->where('is_active', 0)
            ->where('login_method', 0)
            ->first();

        if ($user) {
            $user->is_active = 1;
            $user->save();
            Auth::guard('user')->login($user);
            $_SESSION["RF"]["subfolder"] = "uploads/users/$user->user_code";
            Toastr::success("Kích hoạt tài khoản thành công!");

            return redirect()->route('user.personal-info');
        }

        Toastr::success("Tài khoản đã được kích hoạt vui lòng đăng nhập");
        return redirect()->route('home.index');
    }
}
