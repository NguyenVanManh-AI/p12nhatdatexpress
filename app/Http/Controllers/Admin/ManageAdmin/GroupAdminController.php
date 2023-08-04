<?php
// 30/12/2021 10:30 | Chinh them function liet ke cac nhom quan tri
namespace App\Http\Controllers\Admin\ManageAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageAdmin\AddGroupManageRequest;
use App\Http\Requests\Admin\ManageAdmin\UpdateGroupManageRequest;
use App\Models\Admin;
use App\Models\Admin\Page;
use App\Models\PagePermission;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupAdminController extends Controller
{
    //------------------------------------------------------------------------LIST------------------------------------------------------------------------//
    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        $roles = Role::select('id', 'show_order', 'role_name', 'created_by');
        $trash_num = Role::onlyIsDeleted();

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $roles = Role::select('role.id', 'role.show_order', 'role.role_name', 'role.created_by', 'rol_id', 'role.id', 'role.role_name')
                    ->join('admin', 'role.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id, ['role.id', '<>', '1']]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $roles = $roles->where(['role.created_by' => $admin_id, ['role.id', '<>', '1']]);
            } else { // all || check
                $roles = $roles->where('role.id', '<>', '1');
            }
            // check request_trash_list_scope
            if ($request->request_trash_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $trash_num = $trash_num->join('admin', 'role.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id]);
            } else if ($request->request_trash_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $trash_num = $trash_num->where(['role.created_by' => $admin_id]);
            }
        }

        // final check
        $roles = $roles->orderBy('show_order', 'asc')->orderBy('id', 'desc')->paginate($items);
        $trash_num = $trash_num->count('role.id');

        return view('Admin.ManageAdmin.Group', compact('roles', 'trash_num'));
    }

    public function list_trash(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        $roles_trash = Role::select('id', 'show_order', 'role_name', 'created_by')
            ->onlyIsDeleted();

        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $roles_trash = Role::select('role.id', 'role.show_order', 'role.role_name', 'role.created_by', 'rol_id', 'role.id', 'role.role_name')
                ->join('admin', 'role.created_by', '=', 'admin.id')
                ->where(['rol_id' => $admin_role_id]);
        } else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;
            $roles_trash = $roles_trash->where(['created_by' => $admin_id]);
        }

        $roles_trash = $roles_trash->orderBy('show_order', 'asc')->orderBy('id', 'desc')->paginate($items);

        return view('Admin.ManageAdmin.TrashGroup', compact('roles_trash'));
    }

    //------------------------------------------------------------------------CREATE------------------------------------------------------------------------//
    public function add(Request $request)
    {
        $pages = Page::where('page_parent_id', null)->select('id', 'page_name', 'page_icon', 'is_duplicate', 'is_page_file')
            ->orderBy('show_order', 'asc')->get();
        $page_permission = PagePermission::select('id', 'permission_type', 'permission_name', 'is_file', 'is_duplicate')->get();

        return view('Admin.ManageAdmin.AddGroup', compact('pages', 'page_permission'));
    }

    public function store(AddGroupManageRequest $request)
    {
        if ($request->title == 'Tài khoản quản trị cao cấp') {
            Toastr::error('Vui lòng kiểm tra các trường');
            return redirect()->back()->withInput($request->input());
        }
        $permission_on_page = [];
        foreach ($request->page as $page => $page_value) {
            if ($page_value != -1) {
                foreach ($page_value as $permisson => $permission_value) {
                    if (!isset($permission_value['check']) || $permission_value['check'] != 1) {
                        unset($page_value[$permisson]);
                    } else {
                        $page_value[$permisson] = $permission_value;
                    }
                }
                $permission_on_page[$page] = $page_value;
            }
        }
        if (count($permission_on_page) <= 0) {
            Toastr::error('Vui lòng chọn quyền');
            return redirect()->back()->withInput($request->input());
        }
        Role::create([
                'role_name' => $request->title,
                'role_content' => serialize($permission_on_page),
                'show_order' => 0,
                'created_by' => Auth::guard('admin')->user()->id,
                'created_at' => time()
            ]);
        // $data =[
        //     'role_name' => $request->title,
        //     'role_content' => serialize($permission_on_page),
        //     'show_order' => 0,
        //     'created_by' => Auth::guard('admin')->user()->id,
        //     'created_at' => strtotime('now')
        // ];
        // Helper::create_admin_log(15,$data);

        Toastr::success('Thêm nhóm thành công');
        return redirect()->route('admin.manage.group');
    }

    //------------------------------------------------------------------------UPDATE------------------------------------------------------------------------//
    public function edit($id)
    {
        $role = Role::select('id', 'role_name', 'role_content', 'created_by')
            ->findOrFail($id);

        if ($role->id == 1 && Auth::guard('admin')->user()->admin_type != 1) {
            Toastr::warning('Không đủ quyền');
            return redirect()->back();
        }

            $role->role_content = unserialize($role->role_content);
            $pages = Page::where('page_parent_id', null)
                ->select('id', 'page_name', 'page_icon', 'is_duplicate', 'is_page_file')
                ->orderBy('show_order', 'asc')
                ->get();
            $page_permission = PagePermission::select('id', 'permission_type', 'permission_name', 'is_file', 'is_duplicate')
                ->get();

            return view('Admin.ManageAdmin.EditGroup', compact('role', 'pages', 'page_permission'));
    }

    public function update(UpdateGroupManageRequest $request, int $id)
    {
        $role = Role::findOrFail($id);

        if ($role->id == 1 && Auth::guard('admin')->user()->admin_type != 1) {
            Toastr::warning('Không đủ quyền');
            return redirect()->back();
        }
        $permission_on_page = [];
        foreach ($request->page as $page => $page_value) {
            if ($page_value != -1) {
                foreach ($page_value as $permisson => $permission_value) {
                    if (!isset($permission_value['check']) || $permission_value['check'] != 1) {
                        unset($page_value[$permisson]);
                    } else {
                        $page_value[$permisson] = $permission_value;
                    }
                }
                $permission_on_page[$page] = $page_value;
            }
        }
        if (count($permission_on_page) <= 0) {
            Toastr::error('Vui lòng chọn quyền');
            return redirect()->back();
        }
        $role->update([
            'role_name' => $request->title,
            'role_content' => serialize($permission_on_page),
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => strtotime('now'),
        ]);
        //     $data =[
        //         'role_name' => $request->title,
        //         'role_content' => serialize($permission_on_page),
        //         'updated_by' => Auth::guard('admin')->user()->id,
        //         'updated_at' => strtotime('now'),
        //     ];
        // Helper::create_admin_log(16,$data);

        Toastr::success('Cập nhật nhóm thành công');
        return back();
        return redirect(route('admin.manage.group'));
    }

    public function update_show_order(Request $request)
    {
        $result = true;
        $ids_fail = [];
        if (!is_array($request->select_item) || !is_array($request->show_order)) {
            Toastr::error('Chưa mục nào được chọn!');
            return redirect()->back();
        }

        for ($i = 0; $i < count($request->select_item); $i++) {
            $id = $request->select_item[$i];
            $show_value = $request->show_order[$id];
            $role = Role::find($id);

            if (!is_numeric($show_value) || !$role) {
                $ids_fail[] = $id;
                $result = false;
                continue;
            }
            $role->update([
                'show_order' => $show_value
            ]);
        }

        if ($result) {
            Toastr::success('Cập nhật thứ tự thành công!');
        } else {
            Toastr::error('Cập nhật không thành công nhóm quản trị có ID là: ' . implode(', ', $ids_fail) . '!');
        }
        return redirect()->back();
    }

    public function restore($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        Role::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->restore();
                // should check updated at updated by
                // 'updated_at' => time(), 'updated_by' => Auth::guard('admin')->user()->id
            });

        Toastr::success('Khôi phục thành công !');
        return redirect()->back();
    }

    //------------------------------------------------------------------------DELETE------------------------------------------------------------------------//
    public function delete($ids)
    {
        if (!is_array($ids)) $ids = explode(',', $ids);
        $result = true;
        $ids_fail = [];

        foreach ($ids as $id) {
            $is_exist = Admin::where('rol_id', $id)->count();
            $role = Role::find($id);

            if (!$role || $is_exist || ($role->id == 1 && Auth::guard('admin')->user()->admin_type != 1)) {
                $result = false;
                $ids_fail[] = $id;
                continue;
            }

            $role->delete();
            // Role::where('id', $id)->update(['is_deleted' => 1, 'updated_at' => strtotime('now'), 'updated_by' => Auth::guard('admin')->user()->id]);
            // Helper::create_admin_log(18,['is_deleted' => 1, 'updated_at' => strtotime('now'), 'updated_by' => Auth::guard('admin')->user()->id]);
        }

        if ($result)
            Toastr::success('Xóa thành công');
        else
            Toastr::error("Xóa không thành công các nhóm quản trị có ID là: " . implode(', ', $ids_fail) . ". Vì đang được sử dụng.");
        return redirect()->back();
    }

    public function force_delete($ids)
    {
        if (!is_array($ids)) $ids = explode(',', $ids);
        $result = true;
        $ids_fail = [];

        foreach ($ids as $id) {
            $is_exist = Admin::where('rol_id', $id)->count();
            $role = Role::find($id);

            if (!$role || $is_exist || ($role->id == 1 && Auth::guard('admin')->user()->admin_type != 1)) {
                $result = false;
                $ids_fail[] = $id;
                continue;
            }
            $role->forceDelete();
            // Helper::create_admin_log(19,$role);
        }

        if ($result)
            Toastr::success('Xóa thành công');
        else
            Toastr::error("Xóa không thành công các nhóm quản trị có ID là: " . implode(', ', $ids_fail) . ". Vì đang được sử dụng.");
        return redirect()->back();
    }

    //------------------------------------------------------------------------ACTION---------------------------------------------------------------------//
    public function action(Request $request)
    {
        if ($request->select_item == null || !is_array($request->select_item)) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        if ($request->query('action') == "trash") {
            $this->delete(array_values($request->select_item));
        }
        if ($request->query('action') == "forceDelete") {
            $this->force_delete(array_values($request->select_item));
        }
        if ($request->query('action') == "restore") {
            $this->restore(array_values($request->select_item));
        }
        if ($request->query('action') == "update") {
            for ($i = 0; $i < count($request->select_item); $i++) {
                $value = $request->show_order[$request->select_item[$i]];

                $role = Role::find($request->select_item[$i]);

                if (!$role) continue;

                $role->update([
                    'show_order' => $value,
                    'updated_at' => time(),
                    'updated_by' => Auth::guard('admin')->user()->id
                ]);
            }
            Toastr::success("Thành công");
        }

        return redirect()->back();
    }
}
