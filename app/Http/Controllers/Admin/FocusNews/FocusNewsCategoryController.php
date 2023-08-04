<?php

namespace App\Http\Controllers\Admin\FocusNews;

use App\Helpers\CollectionHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\GroupService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class FocusNewsCategoryController extends Controller
{
    protected $table = 'group';
    protected $id_group = 47;
    protected $var_array = array(47);
    private GroupService $groupService;

    public function __construct()
    {
        $this->groupService = new GroupService;
    }

    public function index(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        $group = new Collection([]);
        foreach ($this->var_array as $item){
            $test =$this->groupService->getChildren($item);
            $group = $group->merge($test);
        }
        $parent_group = new Collection();
        $parent_group = $this->groupService->prepareData($group);

        $trashGroup = new Collection([]);
        foreach ($this->var_array as $item){
            $children = $this->groupService->getChildren($item, true);
            $trashGroup =  $trashGroup->merge($children);
        }
        $count_trash = $this->groupService->prepareData($trashGroup, true)->count();

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $parent_group = $parent_group->where('rol_id', $admin_role_id);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $parent_group = $parent_group->where('created_by' ,$admin_id);
            }
        }

        $group = CollectionHelper::paginate($parent_group,$items);

        //vinh thêm count trash
        return view('Admin.FocusNews.ListCategory', compact('group', 'count_trash'));
    }

    public function add_new()
    {
        //Danh sách danh mục
        $group = Group::select('id', 'group_name')->where('parent_id', 47)->get();

        return view('Admin.FocusNews.AddCategory', compact('group'));
    }

    public function postadd_new(Request $request)
    {
        $validate = $request->validate([
            'group_name' => 'required|max:100|min:3|unique:group,group_name',
            'image_url' => 'max:5120',
            'group_url' => 'required|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:group,group_url',
            'group_description' => 'max:50000|nullable',
        ], [
            'group_name.required' => 'Tiêu đề không được để trống',
            'group_name.max' => 'Tiêu đề từ 3 - 100 kí tự',
            'group_name.min' => 'Tiêu đề từ 3 - 100 kí tự',
            'group_name.unique' => 'Tiêu đề đã tồn tại',
            'image_url.image' => 'Chỉ cho phép tải lên hình ảnh',
            'image_url.max' => 'Ảnh quá dung lượng 5Mb',
            'group_url.required' => 'Đường dẫn tĩnh không được để trống',
            'group_url.max' => 'Đường dẫn tĩnh cho phép từ 1 - 255 kí tự',
            'group_url.regex' => 'Đường dẫn tĩnh không hợp lệ',
            'group_url.unique' => 'Đường dẫn tĩnh đã tồn tại',
            'group_description.max' => 'Độ dài cho phép phải ít hơn 50.000 kí tự'
        ]);
        $data = [
            'group_name' => $request->group_name,
            'group_url' => $request->group_url,
            'group_type' => 1,
            'group_description' => $request->group_description,
            'meta_title' => $request->group_name,
            'meta_key' => $request->group_name,
            'meta_desc' => $request->group_name,
            'created_at' => time(),
            'image_url'=>$request->image_url,
            'created_by' => Auth::guard('admin')->user()->id,
        ];

//        if ($request->hasFile('image_url')) {
//            $data['image_url'] = HelperImage::saveImage('system/img/focus-news', $request->file('image_url')->getClientOriginalExtension(), $request->file('image_url'));
//        }
        if ($request->has('parent_id') && $request->parent_id != null) {
            $data['parent_id'] = $request->parent_id;
        } else
            $data['parent_id'] = 47;
        $id = Group::insertGetId($data);
        if ($id == null) {
            Toastr::warning("Không thành công");
            return back();
        } else {
            Helper::create_admin_log(101,$data);
            Toastr::success("Thêm danh mục thành công");
            return redirect(route('admin.focuscategory.list'));
        }
    }

    //------------------------------------------------------------------------UPDATE------------------------------------------------------------------------//
    public function edit($id)
    {
        $item = Group::findOrFail($id);

            $parent_group = DB::table(DB::raw("(select id,group_name,group_url,created_by,parent_id from `group`
                         order by parent_id, id) `group`,
                        (select @pv := '" . $this->id_group . "') initialisation"))
                ->whereRaw("find_in_set(parent_id, @pv) > 0")
                ->whereRaw("@pv := concat(@pv, ',', id)")
                ->get();
            $parent_group = collect($parent_group)->filter(function ($value, $key) use ($id) {
                return $value->id != $id;
            });
            return view('Admin.FocusNews.EditCategory', compact('item', 'parent_group'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'group_name' => 'required|unique:' . $this->table . ',group_name,' . $id . '|min:3|max:100',
            'group_url' => 'required|max:255|unique:' . $this->table . ',group_url,' . $id,
            'image_url' => 'max:5120',
            'meta_title' => 'max:255|nullable',
            'group_description' => 'max:50000|nullable',
            'group_type' => 'integer|nullable',
            'parent_id' => 'integer|nullable',
            'meta_key' => 'max:255|nullable',
            'meta_desc' => 'max:255|nullable',
        ], [
            'group_name.required' => 'Tiêu đề không được để trống',
            'group_name.min' => 'Tiêu đề từ 3 - 100 ký tự',
            'group_name.max' => 'Tiêu đề từ 3 - 100 ký tự',
            'group_name.unique' => 'Tên tiêu đề đã tồn tại',
            'group_url.unique' => 'Đường dẫn tĩnh đã tồn tại',
            'group_url.required' => 'Đường dẫn không được để trống',
            'image_url.image' => 'Chỉ cho phép tải lên hình ảnh',
            'image_url.max' => 'Ảnh quá dung lượng 5Mb',
            'group_url.max' => 'Đường dẫn phải dưới 255 ký tự',
            'meta_title.max' => 'Tiêu đề của trang phải dưới 255 kí tự',
            'group_description.max' => 'Mô tả ngắn phải dưới 50000 ký tự',
            'group_type.integer' => 'Loại nhóm phải là số nguyên',
            'parent_id.integer' => 'ID danh mục cha phải là số nguyên',
            'meta_key.max' => 'Từ khóa phải dưới 255 kí tự',
            'meta_desc.max' => 'Mô tả phải dưới 255 kí tự',
        ]);
        $item = Group::where('id', $id)->first();
        $data = [
            'group_name' => $request->group_name,
            'group_url' => $request->group_url,
            'group_description' => $request->group_description,
            'group_type' => $request->group_type,
            'parent_id' => 47,
            'meta_title' => $request->meta_title,
            'meta_key' => $request->meta_key,
            'meta_desc' => $request->meta_desc,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => strtotime('now'),
            'image_url'=>$request->image_url,
        ];
        if ($request->has('parent_id') && $request->parent_id != null) {
            $data['parent_id'] = $request->parent_id;
        }

        Group::where('id', $id)->update($data);
        $data['id'] =$id;
        Helper::create_admin_log(102,$data);
        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.focuscategory.list'));
    }

    //Vinh xóa danh mục
    //------------------------------------------------------------------------DELETE------------------------------------------------------------------------//
    //thùng rác: Vinh + Chinh
    public function list_trash(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        $group = new Collection([]);
        foreach ($this->var_array as $item){
            $test =$this->groupService->getChildren($item, true);
            $group = $group->merge($test);
        }
        $parent_group = new Collection();
        $parent_group = $this->groupService->prepareData($group, true);

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $parent_group = $parent_group->where('rol_id', $admin_role_id);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $parent_group = $parent_group->where('created_by' ,$admin_id);
            }
        }

        $group = CollectionHelper::paginate($parent_group,$items);
        return view('Admin.FocusNews.TrashCategory', compact('group'));
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Group::query()
            ->find($ids)
            ->each(function($item) {
                foreach ($item->children()->get() as $child) {
                    $child->children()->each(function($grandChild) {
                        $grandChild->delete();
                    });
                    $child->delete();
                }
                $item->delete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Group::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                foreach ($item->children()->withIsDeleted()->get() as $child) {
                    $child->children()->withIsDeleted()->each(function($grandChild) {
                        $grandChild->restore();
                    });
                    $child->restore();
                }

                $item->restore();
            });

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Group::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                foreach ($item->children()->withIsDeleted()->get() as $child) {
                    $child->children()->withIsDeleted()->each(function($grandChild) {
                        $grandChild->forceDelete();
                    });
                    $child->forceDelete();
                }
                $item->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }
}
