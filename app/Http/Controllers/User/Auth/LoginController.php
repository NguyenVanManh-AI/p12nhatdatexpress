<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\ResetPassword;
use App\Jobs\SendUserEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * User Authentication
 */
class LoginController extends Controller
{
    /**
     * User login
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_login(Request $request)
    {
        $data = [
            'username' => $request->username,
            'password' => $request->password,
            'login_method' => 0,
            'is_active' => 1,
            // 'is_forbidden' => 0,
            // 'is_deleted' => 0
        ];

        // account check and recovery
        $check_user = User::firstWhere('username', $request->username);

        // check is user deleted
        if (!$check_user || $check_user->isDeleted() || !Auth::guard('user')->attempt($data)) {
            Toastr::error("Tên đăng nhập hoặt mật khẩu không đúng!");
            return back();
        }

        $user = Auth::guard('user')->user();
        session_start();
        $_SESSION["RF"]["subfolder"] = "users/$user->user_code";

        Toastr::success("Đăng nhập thành công!");
        return redirect(session()->pull('url.intended') ?: route('user.personal-info'));
    }

    /**
     * User reset password
     * @param ResetPassword $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_reset_password(ResetPassword $request)
    {

        $user = User::where('email', $request->email)
            ->where('login_method', 0)
            ->where('is_confirmed', 1)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->first();

        if ($user) {
            $newPassword = Str::random(10);
            $user->password = Hash::make($newPassword);
            $user->save();

            $mailTemplate = DB::table('admin_mail_template')->where('template_action', 'dat-lai-mat-khau')->first();
            $mailConfig = DB::table('admin_mail_config')->where('is_deleted', 0)->first();
            if ($mailTemplate && $mailConfig) {
                $title = $mailTemplate->template_title;
                $message = str_replace('%new_password%', $newPassword, $mailTemplate->template_content);
                $sendUserEmailJob = new SendUserEmail($mailConfig->mail_host, $mailConfig->mail_port, $mailConfig->mail_encryption, $mailConfig->mail_username, $mailConfig->mail_password, 'khangdh.it@gmail.com', $mailConfig, $title, $message);
                dispatch($sendUserEmailJob);
                Toastr::success('Mật khẩu mới đã được gửi vào email!');
            } else {
                Toastr::error('Có lỗi xẩy ra, vui lòng liên hệ Admin!');
            }

        } else {
            Toastr::error('Email không hợp lệ!');
        }

        return redirect()->back();

    }
}
