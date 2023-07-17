<?php

namespace App\Http\Controllers\Admin\ManageAdmin;


use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Helpers\CollectionHelper;
use App\Models\ChatHistory;
use App\Models\History;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ListAccountController extends Controller
{
        public function listAccount(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $admin_id = Auth::guard('admin')->user()->id;
        $admin_role_id = Auth::guard('admin')->user()->rol_id;
        $admin_type = Auth::guard('admin')->user()->admin_type;
        $list_account = Admin::query()
            ->select('admin.id', 'admin_username', 'admin_fullname', 'admin_email', 'image_url', 'admin_type', 'admin.created_by', 'admin.created_at', 'role.role_name', 'is_customer_care')
            ->leftJoin('role', 'rol_id', '=', 'role.id');

        if ($admin_type != 1) {
            if ($request->request_list_scope == 2) { // group
                $list_account = $list_account->where(['admin.rol_id' => $admin_role_id, ['admin_type', '>', $admin_type]]);
            } else if ($request->request_list_scope == 3) { //self
                $list_account = $list_account->where(['admin.created_by' => $admin_id, ['admin_type', '>', $admin_type]]);
            } else { // all || check
                $list_account = $list_account->where([['admin_type', '>', $admin_type]]);
            }
        }

        $list_account = $list_account->where('admin.id','<>', $admin_id)->orderBy('id', 'desc')->paginate($items);
        $roles = Role::select('id', 'role_name');
        if ($admin_type != 1){
            $roles = $roles->where('id','>', 1);
        }
        $roles = $roles->get();

        $count_trash = Admin::onlyIsDeleted()->count();
        return view('Admin/ManageAdmin/ListAccount', compact('list_account', 'count_trash', 'roles'));
    }

    public function trash_account(Request $request)
    {
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        $admin_id = Auth::guard('admin')->user()->id;
        $admin_role_id = Auth::guard('admin')->user()->rol_id;
        $admin_type = Auth::guard('admin')->user()->admin_type;
        $trash_account = Admin::query()
            ->select('admin.id', 'admin_username', 'admin_fullname', 'admin_email', 'image_url', 'admin_type', 'admin.created_by', 'admin.created_at', 'role.role_name')
            ->leftJoin('role', 'rol_id', '=', 'role.id');

        if ($admin_type != 1) {
            if ($request->request_list_scope == 2) { // group
                $trash_account = $trash_account->where(['admin.rol_id' => $admin_role_id, ['admin_type', '>', $admin_type]]);
            } else if ($request->request_list_scope == 3) { //self
                $trash_account = $trash_account->where(['admin.created_by' => $admin_id, ['admin_type', '>', $admin_type]]);
            } else { // all || check
                $trash_account = $trash_account->where([['admin_type', '>', $admin_type]]);
            }
        }
        $trash_account = $trash_account->onlyIsDeleted()
            ->latest('id')
            ->paginate($items);

        return view('Admin/ManageAdmin/TrashAccount', compact('trash_account'));
    }

    public function deleteaccount($id)
    {
        $admin = Admin::findOrFail($id);

        $admin->delete();
        // Helper::create_admin_log(22,$admin);

        return back()->with('status', 'Chuyển vào thùng rác thành công');
    }

    public function addaccount()
    {

        return view('Admin.ManageAdmin.ListAccount');
    }

    public function postaccount(Request $request)

    {

        $request->admin_email=strtolower($request->admin_email);

        $validate = $request->validate([
            'admin_username' => 'required|unique:admin,admin_username|min:8|max:20|regex:/^[A-Za-z0-9_]+$/',
            'admin_fullname' => 'required|min:8|max:50',
            'admin_email' => 'required|email|unique:admin,admin_email|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]+)$/',
            'admin_password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|min:8|max:20|same:admin_password',
            'rol_id' => 'required',
            'file' => 'required',

        ], [
            'admin_username.required' => 'Tên đăng nhập không được bỏ trống',
            'admin_username.unique' => 'Tên đăng nhập đã tồn tại',
            'admin_username.min' => 'Tên đăng nhập từ 8 - 20 ký tự, không chứa ký tự đặc biệt và dấu câu',
            'admin_username.max' => 'Tên đăng nhập từ 8 - 20 ký tự, không chứa ký tự đặc biệt và dấu câu',
            'admin_username.regex' => 'Tên đăng nhập từ 8 - 20 ký tự, không chứa ký tự đặc biệt và dấu câu',
            'admin_fullname.required' => 'Tên hiển thị không được bỏ trống',
            'admin_fullname.min' => 'Tên hiển thị từ 8 - 50 kí tự',
            'admin_fullname.max' => 'Tên hiển thị từ 8 - 50 kí tự',
            'admin_email.required' => 'Email không được bỏ trống',
            'admin_email.regex' => 'Nhập đúng định dạng email',
            'admin_email.email' => 'Nhập đúng định dạng email',
            'admin_email.unique' => 'Email đã tồn tại',
            'admin_password.required' => 'Mật khẩu không được bỏ trống',
            'admin_password.min' => 'Mật khẩu từ 8-20 kí tự',
            'admin_password.max' => 'Mật khẩu từ 8-20 kí tự',
            'password_confirmation.required' => 'Nhập lại mật khẩu không được bỏ trống',
            'password_confirmation.same' => 'Nhập lại mật khẩu không đúng',
            'password_confirmation.min' => 'Nhập lại mật khẩu từ 8 - 20 kí tự',
            'password_confirmation.max' => 'Nhập lại mật khẩu từ 8 - 20 kí tự',
            'rol_id.required' => 'Vui lòng chọn loại tài khoản ',
            'file.required' => 'Vui lòng tải ảnh đại diện lên ',
            'role_id.required' => 'Vui lòng chọn loại tài khoản ',
        ]);

        if ($request->rol_id == 1 && Auth::guard('admin')->user()->admin_type != 1) {
            Toastr::warning('Không thể thêm loại tài khoản quản trị này');
            return redirect()->back();
        }
        $data = [
            'admin_username' => $request->admin_username,
            'admin_fullname' => $request->admin_fullname,
            'admin_email' => $request->admin_email,
            'admin_type' => 3,
            'password' => Hash::make($request->admin_password),
            'rol_id' => $request->rol_id,
            'is_customer_care' => $request->is_customer_care ?? 0,
            'created_at' =>strtotime(Carbon::now()),
            'created_by' => Auth::guard('admin')->user()->id
        ];

        if ($request->rol_id == 1) {
            $data['admin_type'] = 2;
        } else if ($request->rol_id == -1) {
            if (Auth::guard('admin')->user()->admin_type == 1) {
                $data['admin_type'] = 1;
                $data['rol_id'] = null;
            } else {
                Toastr::warning('Không thể thêm loại tài khoản quản trị này');
                return back();
            }
        }
        if ($data['rol_id'] != null) {
            if (Role::where('id', $data['rol_id'])->count() == 0) {
                Toastr::warning('Không thể thêm loại tài khoản quản trị này');
                return back();
            }
        }
        if ($request->hasFile('file')) {
            $imageName = time() . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('/system/img/avatar-admin'), $imageName);
            $data['image_url'] = $imageName;
        }
        // Get id admin after insert (11/02/2022: Chinh)
        $admin = Admin::create($data);
        // Helper::create_admin_log(20,$data);
        // create a folder for admin with prefix uploads/admin/:id (11/02/2022: Chinh)
        $path = public_path('uploads/admin/' . $admin->id);
        if(!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        Toastr::success('Thêm thành công');
        return back();
    }

    public function editaccount($id)
    {
        $admin = Admin::findOrFail($id);
        $roles = Role::select('id', 'role_name');
        if (Auth::guard('admin')->user()->admin_type != 1){
            $roles = $roles->where('id','>', 1);
        }
        $roles = $roles->get();

        return view('Admin.ManageAdmin.EditAccount', compact('admin', 'roles'));

    }

    public function updateaccount(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validate = $request->validate([
            'admin_username' => 'required|unique:admin,admin_username,' . $id . '|min:8|max:20|regex:/^[A-Za-z0-9_]+$/',
            'admin_fullname' => 'required|min:8|max:50',
            'admin_email' => 'required|email|unique:admin,admin_email,' . $id . '|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]+)$/',
            // 'admin_password'=>'required|min:8|max:20',
            // 'password_confirmation'=>'required|min:8|max:20|same:admin_password',
            'rol_id' => 'required',
            'file' => 'image',
        ], [
            'admin_username.required' => 'Tên đăng nhập không được bỏ trống',
            'admin_username.unique' => 'Tên đăng nhập đã tồn tại',
            'admin_username.min' => 'Tên đăng nhập từ 8 - 20 ký tự, không chứa ký tự đặc biệt và dấu câu',
            'admin_username.max' => 'Tên đăng nhập từ 8 - 20 ký tự, không chứa ký tự đặc biệt và dấu câu',
            'admin_username.regex' => 'Tên đăng nhập từ 8 - 20 ký tự, không chứa ký tự đặc biệt và dấu câu',
            'admin_fullname.required' => 'Tên hiển thị không được bỏ trống',
            'admin_fullname.min' => 'Tài khoản từ 8 - 50 kí tự',
            'admin_fullname.max' => 'Tài khoản từ 8 - 50 kí tự',
            'admin_email.required' => 'Email không được bỏ trống',
            'admin_email.email' => 'Nhập đúng định dạng emmail',
            'admin_email.regex' => '* Nhập đúng định dạng email',
            'admin_email.unique' => 'Email đã tồn tại',
            'admin_password.required' => 'Mật khẩu không được bỏ trống',
            'admin_password.min' => 'Nhập lại mật khẩu từ 8 - 20 kí tự',
            'admin_password.max' => 'Nhập lại mật khẩu từ 8 - 20 kí tự',
            'password_confirmation.required' => 'Nhập lại mật khẩu không được bỏ trống',
            'password_confirmation.same' => 'Nhập lại mật khẩu không đúng',
            'role_id.required' => 'Vui lòng chọn loại tài khoản ',
        ]);


        $edit = [
            'admin_username' => $request->admin_username,
            'admin_fullname' => $request->admin_fullname,
            'admin_email' => $request->admin_email,
            'admin_type' => 3,
            'rol_id' => $request->rol_id,
            'is_customer_care' => $request->is_customer_care ?? 0,
            'updated_at' => strtotime(Carbon::now()),
            'updated_by' => Auth::guard('admin')->user()->id
        ];
        if ($request->rol_id == 1) {
            if (Auth::guard('admin')->user()->admin_type != 1) {
                Toastr::warning('Không thể sửa tài khoản quản trị này');
                return redirect()->back();
            }
            $edit['admin_type'] = 2;
        } else if ($request->rol_id == -1) {
            if (Auth::guard('admin')->user()->admin_type == 1) {
                $edit['admin_type'] = 1;
                $edit['rol_id'] = null;
            } else {
                Toastr::warning('Không thể sửa tài khoản quản trị này');
                return back();
            }
        }
        if ($edit['rol_id'] != null) {
            if (Role::where('id', $edit['rol_id'])->count() == 0) {
                Toastr::warning('Không thể sửa tài khoản quản trị này');
                return back();
            }
        }

        if ($request->has('admin_password') && $request->admin_password != null) {
            if (strlen($request->admin_password) < 8 || strlen($request->admin_password) > 20)
                return redirect()->back()->withErrors(['admin_password' => 'Mật khẩu từ 8-20 kí tự']);
            if (strlen($request->password_confirmation) < 8 || strlen($request->password_confirmation) > 20)
                return redirect()->back()->withErrors(['password_confirmation' => 'Nhập lại mật khẩu từ 8-20 kí tự']);
            if ($request->admin_password !== $request->password_confirmation)
                return redirect()->back()->withErrors(['password_confirmation' => 'Nhập lại mật khẩu không trùng khớp.']);

            $edit['password'] = Hash::make($request->admin_password);
        }

        if ($request->hasFile('file')) {

            $imageName = time() . '.' . $request->file->getClientOriginalExtension();
            // kiểm tra ảnh có tồn tại hay không , kiểm tra trong link có hay không thỏa mới tiến hành xóa
            if (file_exists(public_path('/system/img/avatar-admin/' . $admin->image_url)) && $admin->image_url != "") {

                unlink(public_path('/system/img/avatar-admin/' . $admin->image_url));
            }
            $request->file->move(public_path('/system/img/avatar-admin'), $imageName);
            $edit['image_url'] = $imageName;
        }
        $admin->update($edit);
        // Helper::create_admin_log(21,$edit);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.manage.accounts'));
    }

    public function trash_item($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();
        // Helper::create_admin_log(22,$admin);

        Toastr::success('Xóa thành công');
        return back();
    }

    public function untrash_item($id)
    {
        $admin = Admin::onlyIsDeleted()->findOrFail($id);
        $admin->restore();
        // Helper::create_admin_log(23,$admin);

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function trash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $id) {
            $admin = Admin::find($id);
            if (!$admin) continue;
            $admin->delete();
            // Helper::create_admin_log(22,$admin);
        }

        Toastr::success('Xóa thành công');
        return back();
    }

    public function untrash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $id) {
            $admin = Admin::onlyIsDeleted()->find($id);
            if (!$admin) continue;
            $admin->restore();
            // Helper::create_admin_log(22,$admin);
        }

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Admin::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function update_avatar($id)
    {
        $admin = Admin::findOrFail($id);

        return view('Admin.ManageAdmin.UpdateAvata', compact('admin'));
    }

    public function postavatar(Request $request, $id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return $request->has('is_reload')
                ? abort(404)
                : response()->json([
                    'success' => false
                ], 404);
        }

        if ($request->hasFile('file')) {

            $imageName = time() . '.' . $request->file->getClientOriginalExtension();
            // kiểm tra ảnh có tồn tại hay không , kiểm tra trong link có hay không thỏa mới tiến hành xóa
            if (file_exists(public_path('/system/img/avatar-admin/' . $admin->image_url)) && $admin->image_url != "") {

                unlink(public_path('/system/img/avatar-admin/' . $admin->image_url));
            }
            $request->file->move(public_path('/system/img/avatar-admin'), $imageName);
            $edit['image_url'] = $imageName;
        } else {
            Toastr::warning('Vui lòng chọn ảnh');
            return redirect(route('admin.manage.accounts'));

        }
        $admin->update($edit);
        // Helper::create_admin_log(24,$edit);

        $request->session()->flash('success','Cập Nhật ảnh đại diện thành công');

        if ($request->has('is_reload'))
            return redirect()->back();

        return response()->json("success", 200);
    }


    # Info log
    public function info_log(Request $request, $admin_id){
        $items = $request->items ?? 10;
        $params['admin'] = Admin::query()
            ->where('admin.id', $admin_id)
            ->leftJoin('role', 'admin.rol_id', '=', 'role.id')
            ->first(['admin.id', 'admin_fullname', 'image_url', 'admin_type', 'role_name', 'admin.created_by', 'admin.created_at']);

        if ($params['admin'] == null) abort(404);

        $params['logs'] = DB::table('admin_log_content as alc')
            ->select('alc.log_time', 'al.log_icon', 'al.log_content')
            ->join('admin_log as al', 'al.id', '=', 'alc.log_id')
            ->where('alc.admin_id', $admin_id)
            ->orderBy('alc.id', 'desc')
            ->paginate($items);

        $historiesItems = $request->histories_items ?? 10;
        $params['histories'] = History::where('action_admin_id', $admin_id)
            ->latest()
            ->paginate($historiesItems);

        return view('Admin.ManageAdmin.InfoLog', $params);
    }

    # Show infomation of customer care
    public function info_customer_care(Request $request, $id){
        $items = $request->items ?? 10;
        $admin = Admin::where(['is_customer_care' => 1])->findOrFail($id);
            $history = collect(ChatHistory::where('admin_id', $admin->id)->orderBy('id','desc')->with('user_detail')->get()->toArray());
            $history =  $history->map(function ($item, $key){
                $item = (array) $item;
                $unserialize_value = unserialize($item['chat_message']);
                $list_history_convert = [];
                foreach ($unserialize_value as $ms){
                    $list_history_convert[] = [
                        'chat_code' => $item['chat_code'],
                        'user_id' => $item['user_id'],
                        'admin_id' => $item['admin_id'],
                        'chat_message' => $ms['message'],
                        'type' => $ms['type'],
                        'chat_time' => date('H:s d/m/Y' , $ms['time']),
                        'chat_time_origin' => $ms['time'],
                    ];
                }
                 $item['chat_message'] = $list_history_convert;
                 $item['respontime'] = $item['respontime'] ? $item['respontime'] + $item['chat_message'][0]['chat_time_origin'] : null;
                return $item;
            });
            $history = CollectionHelper::paginate($history, $items);

            // Month
            $first_of_month =  Carbon::now()->startOfMonth()->timestamp;
            $last_of_month =  Carbon::now()->endOfMonth()->timestamp;
            $first_of_before_month =  Carbon::now()->startOfMonth()->subMonthsNoOverflow()->timestamp;
            $last_of_before_month =  Carbon::now()->subMonthsNoOverflow()->endOfMonth()->timestamp;

            // Week
            $first_of_week =  Carbon::now()->startOfWeek()->timestamp;
            $last_of_week =  Carbon::now()->endOfWeek()->timestamp;
            $first_of_before_week =  Carbon::now()->startOfWeek()->subWeek()->timestamp;
            $last_of_before_week =  Carbon::now()->subWeek()->endOfWeek()->timestamp;

            // Conversion
            $conversion_of_previous_month_count = ChatHistory::where('admin_id', $admin->id)->where('created_at', '>=', $first_of_before_month)->where('created_at', '<=', $last_of_before_month)->count();
            $conversion_of_month_count = ChatHistory::where('admin_id', $admin->id)->where('created_at', '>=', $first_of_month)->where('created_at', '<=', $last_of_month)->count();
            $conversion_of_previous_week_count = ChatHistory::where('admin_id', $admin->id)->where('created_at', '>=', $first_of_before_week)->where('created_at', '<=', $last_of_before_week)->count();
            $conversion_of_week_count = ChatHistory::where('admin_id', $admin->id)->where('created_at', '>=', $first_of_week)->where('created_at', '<=', $last_of_week)->count();

            // Percent
            $percent_month = round(($conversion_of_month_count / ($conversion_of_previous_month_count || 1) ) * 100 * ($conversion_of_month_count > $conversion_of_previous_month_count ? 1 : -1));
            $percent_week = round(($conversion_of_week_count / ($conversion_of_previous_week_count || 1) ) * 100 * ($conversion_of_week_count > $conversion_of_previous_week_count ? 1 : -1));

            return view("Admin.ManageAdmin.CustomerCare",
                compact('history', 'admin', 'conversion_of_month_count', 'conversion_of_week_count', 'percent_month', 'percent_week'));
    }
}

