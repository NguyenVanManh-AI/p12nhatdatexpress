<?php

namespace App\Http\Controllers\Admin\StaticPage;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Traits\Filterable;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StaticPageController extends Controller
{
    use Filterable;

    protected $filterable = [
        'keyword' => 'page_title',
        'page_group',
        'created_at',
        'is_highlight',
//        'parent_id' => 'page_group',
    ];
    protected $table = 'static_page';
    public $current_name = null;

    protected function filterKeyword($query, $value)
    {
        return $query->where($this->table . '.' . 'page_title', 'like', "%$value%");
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

        $group_page = StaticPage::query();
        $trash_num = StaticPage::onlyIsDeleted()->count();

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $group_page = $group_page->select($this->table . '.*', 'admin.rol_id')
                    ->join('admin', $this->table . '.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id, $this->table . '.is_show' => 1]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $group_page = $group_page->where([$this->table . '.created_by' => $admin_id]);
            }
        }

        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $group_page = $this->scopeFilter($group_page, $params);
        $group_page = $group_page
//            ->addSelect($this->table . '.*')
//            ->leftJoin('static_page_group', $this->table . '.page_group', 'static_page_group.id')
            ->orderBy('show_order', 'asc')->orderBy('id', 'desc')->paginate($items);
//        $parent_group = DB::table('static_page_group')->select('id', 'group_title')->whereRaw('id in (SELECT page_group from static_page WHERE page_group is not null)')->get();
        return view('Admin.StaticPage.ListStaticPage', compact('group_page', 'trash_num'));
    }

    public function list_trash(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        $group_page = StaticPage::onlyIsDeleted();

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
        $group_page = $group_page
//            ->addSelect($this->table . '.*', 'group_title')
//            ->leftJoin('static_page_group', $this->table . '.page_group', 'static_page_group.id')
            ->orderBy('show_order', 'asc')->orderBy('id', 'desc')->paginate($items);
//        $parent_group = DB::table('static_page_group')->select('id', 'group_title')->whereRaw('id in (SELECT page_group from static_page WHERE page_group is not null)')->get();
        return view('Admin.StaticPage.ListTrashStaticPage', compact('group_page'));
    }

    //------------------------------------------------------------------------CREATE------------------------------------------------------------------------//
    public function add(Request $request)
    {
//        $parent_group = DB::table('static_page_group')->select('id', 'group_title')
//            ->where(['is_show' => 1, 'is_deleted' => 0])
//            ->get();
        return view('Admin.StaticPage.AddStaticPage');
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_title' => 'required|unique:static_page,page_title|min:3|max:100',
//            'page_group' => 'required|integer',
            'image_url' => 'max:255|nullable',
            'page_url' => 'max:255|nullable',
            'meta_title' => 'max:255|nullable',
            'meta_desc' => 'max:255|nullable',
            'meta_key' => 'max:255|nullable',
            'page_description' => 'required|max:255',
            'page_content' => 'required'
        ], [
            'page_title.required' => 'Tiêu đề không được để trống',
            'page_title.min' => 'Tiêu đề phải từ 3 - 100 ký tự',
            'page_title.max' => 'Tiêu đề phải từ 3 - 100 ký tự',
            'page_title.unique' => 'Tiêu đề đã tồn tại',
//            'page_group.required' => 'Phải chọn nhóm',
//            'page_group.integer' => 'Nhóm không hợp lệ',
            'image_url.max' => 'Đường dẫn phải dưới 255 ký tự',
            'page_url.max' => 'Đường dẫn phải dưới 255 ký tự',
            'meta_title.max' => 'Tiêu đề của trang phải dưới 255 kí tự',
            'meta_desc.max' => 'Mô tả phải dưới 255 kí tự',
            'meta_key.max' => 'Từ khóa phải dưới 255 kí tự',
            'page_description.required' => 'Mô tả ngắn không được để trống',
            'page_description.max' => 'Mô tả ngắn phải dưới 255 ký tự',
            'page_content.required' => 'Nội dung không được để trống',
        ]);

        $this->create($request);
        // Helper::create_admin_log(9,$data);

        Toastr::success('Thêm thành công');
        return redirect()->route('admin.static.page');
    }

    function insert($item)
    {
        StaticPage::create([
            'page_title' => $item->page_title,
            'image_url' => $item->image_url,
            'page_description' => $item->page_description,
            'page_content' => $item->page_content,
            'is_highlight' => $item->is_highlight,
//            'page_group' => $item->page_group,
            'page_url' => $item->page_url,
            'meta_title' => $item->meta_title,
            'meta_key' => $item->meta_key,
            'show_order' => $item->show_order ?? 0,
            'meta_desc' => $item->meta_desc,
            'created_by' => Auth::guard('admin')->user()->id,
            'created_at' => time()
        ]);
    }

    //------------------------------------------------------------------------DUPLICATE------------------------------------------------------ ---------------//
    public function generate_name($name)
    {
        $num_exist = StaticPage::where('page_title', $name)->count('id');
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
            $page_group = StaticPage::whereIn('id', $ids)->get();
            foreach ($page_group as $item) {
                $this->current_name = $item->page_title;
                $item->page_title = $this->generate_name($item->page_title);
                $this->insert($item);
                # Note log
                // Helper::create_admin_log(5, "Nhân bản trang tĩnh");
            }
        } else {
            $page_group = StaticPage::where('id', $ids)->first();
            if ($page_group) {
                $this->current_name = $page_group->page_title;
                $page_group->page_title = $this->generate_name($page_group->page_title);
                $page_group->page_url = Str::slug($page_group->page_title);
                $page_group->meta_title = $page_group->page_title;
                $page_group->meta_key = $page_group->page_title;
//                dd($page_group->page_title);
                $this->insert($page_group);
                # Note log
                // Helper::create_admin_log(10, $page_group);
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
        $item = StaticPage::findOrFail($id);

//            $parent_group = DB::table('static_page_group')->select('id', 'group_title')
//                ->where(['is_show' => 1, 'is_deleted' => 0])
//                ->get();
        return view('Admin.StaticPage.EditStaticPage', compact('item'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'page_title' => 'required|unique:static_page,page_title,' . $id . '|min:3|max:100',
//            'page_group' => 'required|integer',
            'image_url' => 'max:255|nullable',
            'page_url' => 'max:255|nullable',
            'meta_title' => 'max:255|nullable',
            'meta_desc' => 'max:255|nullable',
            'meta_key' => 'max:255|nullable',
            'page_description' => 'required|max:255',
            'page_content' => 'required'
        ], [
            'page_title.required' => 'Tiêu đề không được để trống',
            'page_title.min' => 'Tiêu đề phải từ 3 - 100 ký tự',
            'page_title.max' => 'Tiêu đề phải từ 3 - 100 ký tự',
            'page_title.unique' => 'Tiêu đề đã tồn tại',
//            'page_group.required' => 'Phải chọn nhóm',
//            'page_group.integer' => 'Nhóm không hợp lệ',
            'image_url.max' => 'Đường dẫn phải dưới 255 ký tự',
            'page_url.max' => 'Đường dẫn phải dưới 255 ký tự',
            'meta_title.max' => 'Tiêu đề của trang phải dưới 255 kí tự',
            'meta_desc.max' => 'Mô tả phải dưới 255 kí tự',
            'meta_key.max' => 'Từ khóa phải dưới 255 kí tự',
            'page_description.required' => 'Mô tả ngắn không được để trống',
            'page_description.max' => 'Mô tả ngắn phải dưới 255 ký tự',
            'page_content.required' => 'Nội dung không được để trống',
        ]);

        $staticPage = StaticPage::findOrFail($id);

        $staticPage->update([
            'page_title' => $request->page_title,
            'image_url' => $request->image_url,
            'page_description' => $request->page_description,
            'page_content' => $request->page_content,
            'is_highlight' => $request->is_highlight,
//            'page_group' => $request->page_group,
            'page_url' => $request->page_url,
            'meta_title' => $request->meta_title,
            'meta_key' => $request->meta_key,
            'meta_desc' => $request->meta_desc,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => strtotime('now'),
        ]);

        # Note log
        // Helper::create_admin_log(11, $data);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.static.page'));
    }

    public function restore($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        StaticPage::onlyIsDeleted()
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

        StaticPage::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->delete();
                // Helper::create_admin_log(13, ['is_deleted' => 1, 'updated_at' => strtotime('now'), 'updated_by' => Auth::guard('admin')->user()->id]);
            });

        Toastr::success('Xóa thành công');
        return redirect()->back();
    }

    public function force_delete($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        StaticPage::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
                // Helper::create_admin_log(14);
            });

        Toastr::success('Xóa thành công');
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
//                $highlight = $request->is_highlight[$request->select_item[$i]];

                $staticPage = StaticPage::find($request->select_item[$i]);
                $staticPage->update([
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

