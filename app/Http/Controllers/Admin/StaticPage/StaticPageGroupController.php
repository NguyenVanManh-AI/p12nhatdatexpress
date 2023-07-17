<?php

namespace App\Http\Controllers\Admin\StaticPage;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\StaticPageGroup;
use App\Traits\Filterable;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPageGroupController extends Controller
{
    use Filterable;

    protected $filterable = [
        'keyword' => 'group_title',
        'parent_id',
        'created_at',
    ];
    protected $table = 'static_page_group';
    public $current_name = null;

    protected function filterKeyword($query, $value)
    {
        return $query->where($this->table . '.' . 'group_title', 'like', "%$value%");
    }

    protected function filterCreatedAt($query, $value)
    {
        if (key_exists('date_start', $value)) {
            $date_start = strtotime($value['date_start']);
            $query = $query->where($this->table . '.' . 'created_at', '>=', $date_start);
        }
        if (key_exists('date_end', $value)) {
            $end_of_date = Carbon::createFromFormat('Y-m-d', $value['date_end'])->endOfDay()->toDateTimeString();
            $date_end = strtotime($end_of_date);
            $query = $query->where($this->table . '.' . 'created_at', '<=', $date_end);
        }
        return $query;
    }

    //------------------------------------------------------------------------LIST------------------------------------------------------------------------//x
    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        $group_page = StaticPageGroup::query();
        $trash_num = StaticPageGroup::onlyIsDeleted()->count();

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $group_page = $group_page->select($this->table . '.*', 'admin.rol_id')
                    ->join('admin', $this->table . '.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $group_page = $group_page->where([$this->table . '.created_by' => $admin_id]);
            }
        }

        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $group_page = $this->scopeFilter($group_page, $params);
        $group_page = $group_page->orderBy('show_order', 'asc')->orderBy('id', 'desc')->paginate($items);
        $parent_group = StaticPageGroup::select('id', 'group_title')->whereRaw('id in (SELECT parent_id from static_page_group WHERE parent_id is not null)')->get();

        return view('Admin.StaticPage.ListStaticGroup', compact('group_page', 'trash_num', 'parent_group'));
    }

    public function list_trash(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        $group_page = StaticPageGroup::query();
        $trash_num = StaticPageGroup::onlyIsDeleted()->count('id');

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $group_page = $group_page->select($this->table . '.*', 'admin.rol_id')
                    ->join('admin', $this->table . '.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $group_page = $group_page->where([$this->table . '.created_by' => $admin_id]);
            }
        }
        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $group_page = $this->scopeFilter($group_page, $params);
        $group_page = $group_page->orderBy('show_order', 'asc')->orderBy('id', 'desc')->paginate($items);
        $parent_group = StaticPageGroup::select('id', 'group_title')->whereRaw('id in (SELECT parent_id from static_page_group WHERE parent_id is not null)')->get();
        return view('Admin.StaticPage.ListTrashStaticGroup', compact('group_page', 'parent_group'));
    }

    //------------------------------------------------------------------------CREATE------------------------------------------------------------------------//
    public function add(Request $request)
    {
        $parent_group = StaticPageGroup::select('id', 'group_title')
            ->get();

        return view('Admin.StaticPage.AddStaticGroup', compact('parent_group'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_title' => 'required|unique:static_page_group,group_title|min:3|max:100',
            'image_url' => 'max:255|nullable',
            'group_url' => 'max:255|nullable',
            'meta_title' => 'max:255|nullable',
            'meta_description' => 'max:255|nullable',
            'meta_keyword' => 'max:255|nullable'
        ], [
            'group_title.required' => 'Tiêu đề không được để trống',
            'group_title.min' => 'Tiêu đề phải từ 3 - 100 ký tự',
            'group_title.max' => 'Tiêu đề phải từ 3 - 100 ký tự',
            'group_title.unique' => 'Tiêu đề đã tồn tại',
            'image_url.max' => 'Đường dẫn phải dưới 255 ký tự',
//            'image_url.url' => 'Đường dẫn không hợp lệ',
            'group_url.max' => 'Đường dẫn phải dưới 255 ký tự',
            'meta_title.max' => 'Tiêu đề của trang phải dưới 255 kí tự',
            'meta_description.max' => 'Mô tả phải dưới 255 kí tự',
            'meta_keyword.max' => 'Từ khóa phải dưới 255 kí tự',
        ]);
//        if ($request->image_url != null) {
//            if (!filter_var($request->image_url, FILTER_VALIDATE_URL) || !Http::get($request->image_url)->successful()) {
//                return redirect()->back()->withErrors(['image_url' => 'Đường dẫn không hợp lệ.'])->withInput($request->all());
//            }
//        }
        $this->insert($request);
        Toastr::success('Thêm thành công');
        return redirect()->route('admin.static.group');
    }

    function insert($page_group)
    {
        StaticPageGroup::insert([
            'group_title' => $page_group->group_title,
            'image_url' => $page_group->image_url,
            'group_url' => $page_group->group_url,
            'meta_title' => $page_group->meta_title,
            'meta_keyword' => $page_group->meta_keyword,
            'show_order' => $page_group->show_order ?? 0,
            'parent_id' => $page_group->parent_id,
            'meta_description' => $page_group->meta_description,
            'created_by' => Auth::guard('admin')->user()->id,
            'created_at' => strtotime('now')
        ]);
    }

    //------------------------------------------------------------------------DUPLICATE------------------------------------------------------ ---------------//
    public function generate_name($name)
    {
        $num_exist = StaticPageGroup::where('group_title', $name)->count('id');
        if ($num_exist == 0) {
            return $name;
        }
        if ($this->current_name != null && preg_match('/.+\(\d\)/', $this->current_name)) {
            $new_name = $this->current_name . "($num_exist)";
            $this->current_name = null;
            return $this->generate_name($new_name);
        }
        if (preg_match('/.+\(\d\)/', $name)) {
            $stt = substr($name, -2, 1);
            is_numeric($stt) ? $num_exist = $stt + 1 : $num_exist++;
            $new_name = mb_substr($name, 0, -3);
            $new_name .= "($num_exist)";
        } else
            $new_name = $name . "($num_exist)";
        return $this->generate_name($new_name);
    }

    public function duplicate($ids)
    {
        if (is_array($ids)) {
            $page_group = StaticPageGroup::whereIn('id', $ids)->get();
            foreach ($page_group as $item) {
                $this->current_name = $item->group_title;
                $item->group_title = $this->generate_name($item->group_title);
                $this->insert($item);
            }
        } else {
            $page_group = StaticPageGroup::where('id', $ids)->first();
            if ($page_group) {
                $this->current_name = $page_group->group_title;
                $page_group->group_title = $this->generate_name($page_group->group_title);
                $this->insert($page_group);
            } else {
                Toastr::error('Lỗi!');
                return back();
            }
        }

        Toastr::success('Nhân bản thành công!');
        return back();
    }

    //------------------------------------------------------------------------UPDATE------------------------------------------------------------------------//
    public function edit($id)
    {
        $page_group = StaticPageGroup::query()
            ->where('id', $id)
            ->first();

        if ($page_group) {
            $parent_group = StaticPageGroup::select('id', 'group_title')
                ->where('id', '<>', $id)
                ->get();
            return view('Admin.StaticPage.EditStaticGroup', compact('page_group', 'parent_group'));
        } else
            return redirect(route('admin.error.404'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'group_title' => 'required|unique:static_page_group,group_title,' . $id . '|min:3|max:100',
            'image_url' => 'max:255|nullable',
            'group_url' => 'max:255|nullable',
            'meta_title' => 'max:255|nullable',
            'meta_description' => 'max:255|nullable',
            'meta_keyword' => 'max:255|nullable'
        ], [
            'group_title.required' => 'Tiêu đề không được để trống',
            'group_title.min' => 'Tiêu đề phải từ 3 - 100 ký tự',
            'group_title.max' => 'Tiêu đề phải từ 3 - 100 ký tự',
            'group_title.unique' => 'Tiêu đề đã tồn tại',
            'image_url.max' => 'Đường dẫn phải dưới 255 ký tự',
//            'image_url.url' => 'Đường dẫn không hợp lệ',
            'group_url.max' => 'Đường dẫn phải dưới 255 ký tự',
            'meta_title.max' => 'Tiêu đề của trang phải dưới 255 kí tự',
            'meta_description.max' => 'Mô tả phải dưới 255 kí tự',
            'meta_keyword.max' => 'Từ khóa phải dưới 255 kí tự',
        ]);
//        if ($request->image_url != null) {
//            if (!filter_var($request->image_url, FILTER_VALIDATE_URL) || !Http::get($request->image_url)->successful()) {
//                return redirect()->back()->withErrors(['image_url' => 'Đường dẫn không hợp lệ.'])->withInput($request->all());
//            }
//        }
        StaticPageGroup::where('id', $id)->update([
            'group_title' => $request->group_title,
            'image_url' => $request->image_url,
            'group_url' => $request->group_url,
            'meta_title' => $request->meta_title,
            'parent_id' => $request->parent_id,
            'meta_keyword' => $request->meta_keyword,
            'meta_description' => $request->meta_description,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => strtotime('now'),
        ]);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.static.group'));
    }

    public function restore($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        StaticPageGroup::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->restore();
                // Helper::create_admin_log(12, ['is_deleted' => 0, 'updated_at' => strtotime('now'), 'updated_by' => Auth::guard('admin')->user()->id]);
            });

        Toastr::success('Khôi phục thành công!');
        return redirect()->back();
    }

    //------------------------------------------------------------------------DELETE------------------------------------------------------------------------//
    public function delete($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        $result = true;

        StaticPageGroup::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->restore();
                // Helper::create_admin_log(12, ['is_deleted' => 0, 'updated_at' => strtotime('now'), 'updated_by' => Auth::guard('admin')->user()->id]);
            });

        $result = true;
        $ids_fail = [];
        foreach ($ids as $id) {
            $is_exist = StaticPage::where('page_group', $id)->count();
            $is_exist_child = StaticPageGroup::where('parent_id', $id)->count();
            if ($is_exist > 0 || $is_exist_child > 0) {
                $result = false;
                $ids_fail[] = $id;
            } else {
                $staticPageGroup = StaticPageGroup::find($id);
                if (!$staticPageGroup) continue;

                $staticPageGroup->delete();
            }
        }

        $result
            ? Toastr::success('Xóa thành công')
            : Toastr::error("Xóa không thành công các nhóm có ID là: " . implode(', ', $ids_fail) . ". Vì đang được sử dụng.");
        return redirect()->back();
    }

    public function force_delete($ids)
    {
        if (!is_array($ids)) $ids = explode(',', $ids);
        $result = true;
        $ids_fail = [];
        foreach ($ids as $id) {
            $is_exist = StaticPage::where('page_group', $id)->count();
            if ($is_exist) {
                $result = false;
                $ids_fail[] = $id;
            } else {
                $staticPageGroup = StaticPageGroup::onlyIsDeleted()->find($id);
                if (!$staticPageGroup) continue;

                $staticPageGroup->forceDelete();
            }
        }
        if ($result)
            Toastr::success('Xóa thành công');
        else
            Toastr::error("Xóa không thành công các nhóm trang tĩnh có ID là: " . implode(', ', $ids_fail) . ". Vì đang được sử dụng.");
        return back();
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
        } else if ($request->query('action') == "duplicate") {
            $this->duplicate(array_values($request->select_item));
        } else if ($request->query('action') == "delete") {
            $this->force_delete(array_values($request->select_item));
        } else if ($request->query('action') == "restore") {
            $this->restore(array_values($request->select_item));
        } else if ($request->query('action') == "update") {
            for ($i = 0; $i < count($request->select_item); $i++) {
                $value = $request->show_order[$request->select_item[$i]];

                $staticPageGroup = StaticPageGroup::onlyIsDeleted()->find($request->select_item[$i]);
                if (!$staticPageGroup) continue;

                $staticPageGroup->update([
                    'show_order' => $value,
                    'updated_at' => time(),
                    'updated_by' => Auth::guard('admin')->user()->id
                ]);
            }

            Toastr::success("Thành công");
        }

        return back();
    }
}

