<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ForgotPasswordRequest;
use App\Http\Requests\Admin\Auth\UpdatePasswordRequest;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\Admin;
use App\Models\Admin\Page;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** Login
     * @return Application|Factory|View
     */
    public function showLoginForm()
    {
        return view('Admin.Auth.Login');
    }

    /**
     * post login
     * @param App\Http\Requests\Admin\LoginRequest $request
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function login(LoginRequest $request)
    // public function login(Request $request)
    {
        $validate = $request->validate([
                'username' => 'required',
                'password' => 'required|min:8|max:20',
            ],[
                'username.required' => 'Vui lòng nhập tài khoản',
                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.min' => 'Mật khẩu từ 8 - 20 kí tự ',
                'password.max' => 'Mật khẩu từ 8 - 20 kí tự',
            ]);

        // check login admin
        if (Auth::guard('admin')->attempt(['admin_username' => $request->username, 'password' => $request->password])) {
            // Save role to session if admin_type != 1 : Chinh 10:16 05/01/2021
            if (Auth::guard('admin')->user()->admin_type != 1) {
                // set session subfolder responsive file manager (Chinh 11/02/2022)
                session_start();
                $_SESSION["RF"]["subfolder"] = "admin/". Auth::guard('admin')->id() . "";

                // check not has role
                if (!session()->has('role')) hasNoRole();

                // declare page can access
                $role_detail = session()->get('role');
                $key = collect((unserialize($role_detail->role_content)))->keys();
                $page = Page::whereIn('id', $key)->orderBy('show_order', 'asc')->get();

                // successful notification
                Toastr::success("Thành công");

                // return
                return redirect(url($page[0]->page_url));
            }

            Toastr::success("Thành công");
            return redirect(url('/admin'));
        }
        else {
            Toastr::warning("Thất bại");
            return back()->with('status', "Thất bại");
        }

        return redirect(route('admin_login'));
    }

    /**
     * logout
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        if (Auth::guard('admin')) {
            Auth::guard('admin')->logout();

            // clear session php and session of laravel
            session_start();
            session_destroy();
            session()->flush();

            Toastr::success('Đăng xuất thành công');

            return redirect(route('home.index'));
        }

        Toastr::warning("Lỗi không xác định");
        return back();
    }

    /**
     * forgot password
     * @return Application|Factory|View
     */
    public function forgot_password(){
        return view('Admin.Auth.ForgotPassword');
    }

    /**
     * reset password
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send_mail_reset(ForgotPasswordRequest $request){
        $admin = Admin::where('admin_email', $request->email)->first();
        //$admin->notify(new SendLinkReset($admin));
        event(new \App\Events\Admin\Auth\SendLinkReset($admin));

        return back()->with(['success' => "Gửi mail đặt lại mật khẩu thành công"]);
    }

    /**
     * reset password
     * @param Request $request
     * @return Application|Factory|View
     */
    public function reset_password(Request $request, $admin){
        if (!$request->hasValidSignature()){
            abort(401);
        }

        return view('Admin.Auth.ResetPassword');
    }

    public function update_password(UpdatePasswordRequest $request, $admin_id){
        if (!$request->hasValidSignature()){
            abort(401);
        }

        $admin = Admin::findOrFail($admin_id);

        $result = $admin->update([
            'password' => Hash::make($request->password)
        ]);

        return $result
            ? redirect()->route('admin_login')->with(['success' => "Đặt lại mật khẩu thành công"])
            : redirect()->back()->with(['error' => "Đặt lại mật khẩu không thành công"]);
    }
}

/**
 * has no role
 * @return \Illuminate\Http\RedirectResponse|void
 */
function hasNoRole() {
    $role = Role::query()
        ->where('id', Auth::guard('admin')->user()->rol_id)
        ->select('role_name', 'role_content')
        ->first();
    if ($role == null){
        Toastr::warning("Lỗi, Kiểm tra quyền đang có");
        return back();
    }
    session()->put('role', $role);
}
