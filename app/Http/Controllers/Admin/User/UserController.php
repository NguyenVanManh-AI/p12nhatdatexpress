<?php

namespace App\Http\Controllers\Admin\User;

use App\Enums\UserEnum;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdatedBusinessRequest;
use App\Models\Province;
use App\Models\User;
use App\Models\User\UserLevel;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private UserService $userService;

    /**
     * inject UserService into UserController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function user_list(Request $request)
    {
        $param['province'] = Province::select('id', 'province_name')->get();

        // maybe showed()
        $param['level'] = UserLevel::select('id', 'level_name')->get();

        // do not get business account
        $request['not_type_id'] = 3;
        $param['user'] = $this->userService->index($request->all());

        return view('Admin.User.ListUser', $param);
    }

    public function truy_cap($id)
    {
        Auth::guard('user')->loginUsingId($id);
        return redirect(route('user.personal-info'));
    }

    public function edit($id)
    {
        $user = User::whereIn('user_type_id', [1, 2])
            ->findOrFail($id);

        return view('Admin.User.EditBusiness', [
            'user' => $user
        ]);
    }

    public function post_edit(UpdatedBusinessRequest $request, $id)
    {
        $user = User::whereIn('user_type_id', [1, 2])
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
                $ $user = User::find($item);
                if (!$user) continue;

                $this->userService->restoreUser($user);
            }

            Toastr::success("Thành công");
            return back();
        }
    }
}
