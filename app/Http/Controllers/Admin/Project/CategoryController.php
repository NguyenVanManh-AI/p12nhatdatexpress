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
            });

        Toastr::success('Xóa thành công');
        return back();
    }
}
