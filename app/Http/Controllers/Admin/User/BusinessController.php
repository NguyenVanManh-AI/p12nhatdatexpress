<?php

namespace App\Http\Controllers\Admin\User;

use App\Enums\UserEnum;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdatedBusinessRequest;
use App\Jobs\SendUserEmail;
use App\Models\AdminMailConfig;
use App\Models\AdminMailTemplate;
use App\Models\Province;
use App\Models\User;
use App\Models\User\UserLevel;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BusinessController extends Controller
{
    private UserService $userService;

    /**
     * inject UserService into BusinessController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function list(Request $request)
    {
        $param['province'] = Province::all();
        $param['level'] = UserLevel::all();

        // get business account
        $request['type_id'] = 3;
        $param['user'] = $this->userService->index($request->all());

        return view('Admin.User.ListBusiness', compact('param'));
    }

    public function edit($id)
    {
        $user = User::where('user_type_id', 3)
            ->findOrFail($id);

        return view('Admin.User.EditBusiness', [
            'user' => $user
        ]);
    }

    public function post_edit(UpdatedBusinessRequest $request, $id)
    {
        $user = User::where('user_type_id', 3)
            ->findOrFail($id);

        $detail = $user->detail()->firstOrCreate([]);
        $detail->update([
            'tax_number' => $request->tax_number
        ]);

        if ($request->password)
            $this->userService->updatePassword($user, $request->password);

        Toastr::success("Cập nhật thành công");
        return back();
    }

    #Chặn tài khoản
    public function block($id)
    {
        $user = User::findOrFail($id);

        $this->userService->blockUser($user);
        Toastr::success("Chặn thành công");
        return back();
    }

    #mở chặn tài khoản
    public function unblock($id)
    {
        $user = User::findOrFail($id);
        $this->userService->unblockUser($user);

        Toastr::success("Mở chặn thành công");
        return back();
    }

    # cấm tài  khoản
    public function forbidden($id)
    {
        $user = User::findOrFail($id);
        $this->userService->forbiddenUser($user);

        Toastr::success("Cấm thành công");
        return back();
    }

    #mở cấm tài khoản
    public function unforbidden($id)
    {
        $user = User::findOrFail($id);

        $this->userService->unforbiddenUser($user);
        Toastr::success("Mở cấm thành công");
        return back();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $this->userService->deleteUser($user, now()->addDays(UserEnum::DELETED_DAYS * -1)->timestamp);

        Toastr::success("Xóa thành công");

        return back();
    }

    public function restore($id)
    {
        $user = User::findOrFail($id);
        $this->userService->restoreUser($user);

        Toastr::success("Khôi phục thành công");
        return back();
    }

    public function browse($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_confirmed' => 1
        ]);
        // Helper::create_admin_log(165, $data);

        $userDetail = $user->detail()->firstOrCreate();

        // old code
        // if ($userDetail) {
            $mailTemplate = AdminMailTemplate::firstWhere('template_action', 'tai_khoan_duoc_duyet');
            $mailConfig = AdminMailConfig::first();
            if ($mailTemplate && $mailConfig) {
                $title = str_replace('%ten_nguoi_nhan%', $userDetail->fullname, $mailTemplate->template_title);
                $message = str_replace('%ten_doanh_ngiep%', $userDetail->fullname, $mailTemplate->template_content);
                $message = str_replace('%link%', asset(''), $message);
                $sendUserEmailJob = new SendUserEmail(
                    $mailConfig->mail_host,
                    $mailConfig->mail_port,
                    $mailConfig->mail_encryption,
                    $mailConfig->mail_username,
                    $mailConfig->mail_password,
                    // 'khangdh.it@gmail.com',???
                    $user->email,
                    $mailConfig, $title,
                    $message
                );
                dispatch($sendUserEmailJob);
            }
        // }

        Toastr::success('Duyệt tài khoản doanh nghiệp thành công');
        return back();
    }

    public function truy_cap($id)
    {
        Auth::guard('user')->loginUsingId($id);
        $data = [
            'id' => $id
        ];

        // should check create history for admin log in user
        Helper::create_admin_log(166, $data);

        return redirect(route('user.personal-info'));
    }

    public function list_action(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        // Chặn tài khoản
        if ($request->action_method == 'block_account') {
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;

                $this->userService->blockUser($user);
            }

            Toastr::success("Thành công");
            return back();
        }

        if ($request->action_method == 'unblock_account') {
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;

                $this->userService->unblockUser($user);
            }

            Toastr::success("Thành công");
            return back();
        }

        if ($request->action_method == 'forbidden') {
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;

                $this->userService->forbiddenUser($user);
            }

            Toastr::success("Thành công");
            return back();
        }

        if ($request->action_method == 'unforbidden') {
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;

                $this->userService->unforbiddenUser($user);
            }

            Toastr::success("Thành công");
            return back();
        }

        if ($request->action_method == 'delete') {
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;

                $this->userService->deleteUser($user, now()->addDays(UserEnum::DELETED_DAYS * -1)->timestamp);
            }

            Toastr::success("Thành công");
            return back();
        }

        if ($request->action_method == 'restore') {
            foreach ($request->select_item as $item) {
                $user = User::find($item);
                if (!$user) continue;

                $this->userService->restoreUser($user);
            }

            Toastr::success("Thành công");
            return back();
        }
    }
}
