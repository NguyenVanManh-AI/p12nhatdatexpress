<?php

namespace App\Http\Controllers\Admin\Classified;

use App\Http\Controllers\Controller;
use App\Helpers\CollectionHelper;
use App\Http\Requests\Requestions\Admin\Group\GroupAddRequest;
use App\Http\Requests\Requestions\Admin\Group\GroupUpdateRequest;
use App\Models\Group;
use App\Services\GroupService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ClassifiedGroupController extends Controller
{
    private GroupService $groupService;

    public function __construct()
    {
        $this->groupService = new GroupService;
    }

    // should check and fix all. ex: index, delete, restore.. multiple level groups not only check parent.
   protected $var_array = array(2,10,18);

    public function list(Request $request)
    {
        $items = 10;
        if(isset($_GET['items'])){
            $items = $_GET['items'];
        }

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
        $trash_count = $this->groupService->prepareData($trashGroup, true)->count();

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

        return view('Admin.Classified.GroupClassified.ListGroup',compact('group','trash_count'));
    }
    public function list_trash(Request $request){
        $items = 10;
        if(isset($_GET['items'])){
            $items = $_GET['items'];
        }
        $group = new Collection([]);
        foreach ($this->var_array as $item){
            $test =$this->groupService->getChildren($item, true);
            $group=  $group->merge($test);
        }

        $parent_group = new Collection();
        $parent_group = $this->groupService->prepareData($group, true);

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
//                dd($parent_group,$admin_role_id);
                $parent_group = $parent_group->where('rol_id', $admin_role_id);

            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $parent_group = $parent_group->where('created_by' ,$admin_id);
            }
        }

        $group = CollectionHelper::paginate($parent_group,$items);

        return view('Admin.Classified.GroupClassified.TrashGroup',compact('group'));
    }
    //---------------------------------------------Thêm -----------------------------------------------
    public function add(){
        $group_ndb = Group::where('parent_id',2)->get();
        $group_ndct = Group::where('parent_id', 10)->get();
        $group_cmct = Group::whereIn('parent_id', [19, 20])->get();
        return view('Admin.Classified.GroupClassified.NewListGroup',compact('group_ndb','group_ndct','group_cmct'));
    }
    public function  post_add(GroupAddRequest $request){
        Group::create([
           'group_name'=>$request->group_name,
           'group_url'=>$request->group_url,
           'group_type'=>0,
           'image_url'=>$request->image_url,
           'parent_id'=>$request->parent_id,
           'created_at'=>time(),
           'created_by'=>Auth::guard('admin')->user()->id,
        ]);
        //  $data=[
        //      'group_name'=>$request->group_name,
        //      'group_url'=>$request->group_url,
        //      'group_type'=>0,
        //      'image_url'=>$request->image_url,
        //      'parent_id'=>$request->parent_id,
        //      'created_at'=>time(),
        //      'created_by'=>Auth::guard('admin')->user()->id,
        //  ];
        //  Helper::create_admin_log(32,$data);

        Toastr::success("Thành công");

        return redirect(route('admin.groupclassified.list'));
    }
    public function edit($id){

        $group_ndb = Group::where('parent_id', 2)->get();
        $group_ndct = Group::where('parent_id', 10)->get();
        $group_cmct = Group::whereIn('parent_id', [19, 20])->get();

        $item = Group::findOrFail($id);

        return view('Admin.Classified.GroupClassified.UpdateListGroup',compact('group_ndb','group_ndct','group_cmct','item'));
    }
    public function post_edit(GroupUpdateRequest $request,$id){
        $group = Group::findOrFail($id);

        $data = [
            'group_name'=>$request->group_name,
            'group_url'=>$request->group_url,
            'group_type'=>0,
            'image_url'=>$request->image_url,
            'parent_id'=>$request->parent_id,
            'updated_at'=>time(),
            'updated_by'=>Auth::guard('admin')->user()->id,
            'group_description'=>$request->group_description,
        ];
        $group->update($data);
        // Helper::create_admin_log(33,$data);

        Toastr::success("Cập nhật thành công");
        return redirect(route('admin.groupclassified.list'));
    }
    public function trash_item($id){
        $item = Group::findOrFail($id);

        if($item->parent_id == 2 || $item->parent_id ==10 || $item->parent_id ==18){
            $child = Group::where(['parent_id'=>$item->id])->get();
            if($child->count()>0){
                Toastr::error("Vui lòng xóa danh mục con trước");
                return back();
            }
        }
        $item->delete();
        // Helper::create_admin_log(34,[
        //     'is_deleted'=>1,
        // ]);
        Toastr::success("Chuyển vào thùng rác thành công");
        return back();
    }
    public function untrash_item($id){
        $item = Group::onlyIsDeleted()
            ->findOrFail($id);

        $parent = $item->parent()->withIsDeleted()->first();

        // should check all ancestor not only parent
        if ($parent->is_deleted) {
            Toastr::error("Vui lòng khôi phục danh mục cha trước !");
            return back();
        }

        $item->restore();

        // Helper::create_admin_log(35,[
        //     'is_deleted'=>0,
        // ]);
        Toastr::success("Khôi phục danh mục thành công");
        return back();
    }

    //xóa nhiều danh mục
    public function trash_list(Request $request)
    {

        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        // kiểm tra điều kiện trước khi xóa hàng loạt
        foreach ($request->select_item as $value) {
            //kiểm tra có phải là phần tử cha hay không
            // nếu có sẽ kiểm tra phần tử con

            if (isset($request->parent)  && array_key_exists($value, $request->parent)) {
                $children = Group::where('parent_id', $value)->get();
                // có phần tử sẽ kiểm tra con có nằm trong danh sách xóa không ?
                if ($children->count() > 0) {
                    foreach($children as $child){
                        if(!in_array($child->id,$request->select_item)){
                            Toastr::error("Vui lòng chọn cả phần tử con của danh mục muốn xóa !");
                            return back();
                        }
                    }
                }
            }
            foreach ($request->select_item as $item) {
                $group = Group::find($item);

                if (!$group) continue;
                $group->delete();
                // Helper::create_admin_log(34,[
                //     'is_deleted'=>1,
                // ]);
            }
        }
        Toastr::success('Thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        // maybe use group observer
        Group::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                // should delete all ancestors
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

    //khôi phục nhiều danh mục
    public function untrash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        //kiểm tra đk khôi phục
        foreach ($request->select_item as $value) {
            //kiểm tra phần tử có phải phần tử con hay không ?
            if (isset($request->child) && array_key_exists($value, $request->child)) {
                $child = Group::withIsDeleted()->find($value);
                if(!$child) continue;
                $parent = Group::withIsDeleted()->firstWhere('id', $child->parent_id);
                if(!$parent) continue;

                if($parent->is_deleted != 0  && !in_array($parent->id,$request->select_item) ){
                    Toastr::error("Vui lòng chọn cả phần tử cha của danh mục muốn khôi phục !");
                    return back();
                }
            }
        }
        foreach ($request->select_item as $item) {
            $group = Group::onlyIsDeleted()
                ->find($item);

            if (!$group) continue;
            $group->restore();

            // Helper::create_admin_log(35,[
            //     'is_deleted'=>0,
            // ]);
        }
        Toastr::success('Khôi phục thành công');
        return back();
    }
}
