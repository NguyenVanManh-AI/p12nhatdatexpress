<?php

namespace App\Http\Controllers\Admin\Project;
use App\CPU\HelperImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Helpers\CollectionHelper;
use App\Http\Requests\Requestions\Admin\Group\GroupAddRequest;
use App\Http\Requests\Requestions\Admin\Group\GroupUpdateRequest;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class CategoryController extends Controller
{
    private GroupService $groupService;

    public function __construct()
    {
        $this->groupService = new GroupService;
    }

    protected $var_array = array(34);
    protected $id_group = 34;
    public function list( Request $request){

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

        return view('Admin.Project.ListCategory',compact('group','count_trash'));
    }

    public function trash( Request $request){
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
        return view('Admin.Project.TrashCategory',compact('group'));
    }

    public function new(){
        $group_project = Group::where('parent_id',$this->id_group)->get();
         return view('Admin.Project.AddCategory',compact('group_project'));
    }

    public function post_new(GroupAddRequest $request){
        $image="";
        if ($request->hasFile('image_url')) {
            $image= HelperImage::saveImage('system/img/group', $request->file('image_url'));
        }

        Group::create([
           'group_name'=>$request->group_name,
           'group_url'=>$request->group_url,
           'group_type'=>1,
           'image_url'=>$image,
           'parent_id'=>$request->parent_id,
           'created_at'=>time(),
           'created_by'=>Auth::guard('admin')->user()->id,
        ]);

        // $data=[
        //     'group_name'=>$request->group_name,
        //     'group_url'=>$request->group_url,
        //     'group_type'=>1,
        //     'image_url'=>$image,
        //     'parent_id'=>$request->parent_id,
        //     'created_at'=>time(),
        //     'created_by'=>Auth::guard('admin')->user()->id,
        // ];
        // Helper::create_admin_log(48,$data);

        Toastr::success("Thành công");
        return  redirect(route('admin.projectcategory.list'));
    }

    public function edit($id)
    {
        // $group_project = Group::where('parent_id',34)->get();
        // return view('Admin.Project.AddCategory',compact('group_project'));

        $item = Group::findOrFail($id);

        // paradigms of nha-dat-ban, nha-dat-cho-thue, can-mua, can-thue, du-an
        $paradigms = Group::whereIn('parent_id', [34, 2, 10, 19, 20])
            ->get();

        $projectParadigms = $paradigms->where('parent_id', 34);
        $dependencyParadigms = $paradigms->whereIn('parent_id', [2, 10, 19, 20])
            ->transform(function ($paradigm) {
                return [
                    'id' => $paradigm->id,
                    'group_name' => $paradigm->group_name,
                    'label' => data_get($paradigm->parent, 'group_name')
                        ? data_get($paradigm->parent, 'group_name') . ' - ' . $paradigm->group_name
                        : $paradigm->group_name
                ];
            });
        // $dependencyParadigms == Group::whereIn('parent_id', [34, 2, 10, 19, 20])
        // ->get();

        // return view('Admin.Project.EditCategory',compact('group_project','item'));
        return view('Admin.Project.EditCategory', [
            'item' => $item,
            'projectParadigms' => $projectParadigms,
            'dependencyParadigms' => $dependencyParadigms,
        ]);
    }

    public function post_edit(GroupUpdateRequest $request,$id){
        $item = Group::findOrFail($id);

        $image = "";
        if ($request->hasFile('image_url')
            &&
            file_exists(public_path('system/img/group',$item->image_url))&& $item->image_url!="") {
            $image= HelperImage::updateImage('system/img/group', $request->file('image_url'), public_path('system/img/group',$item->image_url));
            $data['image_url'] = $image;
        }
        $data = [
            'group_name'=>$request->group_name,
            'group_url'=>$request->group_url,
            'group_type'=>1,
            'parent_id'=>$request->parent_id,
            'updated_at'=>time(),
            'updated_by'=>Auth::guard('admin')->user()->id,
        ];
        $item->update($data);
        $this->groupService->syncParadigmDependencies($item, $request->dependencies ?: []);

        // Helper::create_admin_log(49,$data);
        Toastr::success("Cập nhật thành công");
        return redirect(route('admin.projectcategory.list'));
    }

    public function trash_item($id)
    {
        // maybe check id = 34 for project group. should add key for group table
        $group = Group::findOrFail($id);

        // should check confirm should delete child or not
        // old is check children
        if($group->children->count()) {
            Toastr::error('Vui lòng xóa danh mục con trước');
            return back();
        }

        $group->delete();
        // Helper::create_admin_log(50,['is_deleted'=>1]);

        Toastr::success("Xóa thành công");
        return back();
    }

    public function untrash_item($id){
        $group = Group::onlyIsDeleted()->findOrFail($id);

        if ($group->parent()->onlyIsDeleted()->first()) {
            Toastr::error("Vui lòng khôi phục danh mục cha trước !");
            return back();
        }

        $group->restore();

        // if($item->parent_id == 34){
        //     Group::where('id',$id)->update([
        //         'is_deleted'=>0,
        //     ]);
        //     Helper::create_admin_log(51,['is_deleted'=>0]);

        //     Toastr::success("Khôi phục danh mục thành công ");
        //     return back();
        // }

    //    $parent = Group::find($item->parent_id);
    //         if($parent->is_deleted == 1){
    //             Toastr::error("Vui lòng khôi phục danh mục cha trước !");
    //             return back();
    //         }

    //     Group::where('id',$id)->update([
    //         'is_deleted'=>0,
    //     ]);
    //     Helper::create_admin_log(51,['is_deleted'=>0]);

        Toastr::success("Khôi phục danh mục thành công");
        return back();
    }

    //xóa nhiều danh mục
    public function trash_list(Request $request)
    {

        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
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
                // Group::where('id', $item)->update(['is_deleted' => 1]);
                // Helper::create_admin_log(50,['is_deleted'=> 1]);
            }
        }

        Toastr::success('Thành công');
        return back();
    }


    //khôi phục nhiều danh mục
    public function untrash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        //kiểm tra đk khôi phục
        foreach ($request->select_item as $value) {
            //kiểm tra phần tử có phải phần tử con hay không ?
            if (isset($request->child) && array_key_exists($value, $request->child)) {
                $child = Group::withIsDeleted()->find($value);
                $parent = Group::withIsDeleted()->where(['id'=>$child->parent_id])->first();

                if($parent== null){
                    Toastr::error("Đã xảy ra lỗi");
                    return back();
                }

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
            // Helper::create_admin_log(51,['is_deleted'=> 0]);
        }

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
