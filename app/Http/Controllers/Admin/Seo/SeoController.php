<?php

namespace App\Http\Controllers\Admin\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CollectionHelper;
use App\Models\Group;
use App\Models\HomeConfig;
use App\Services\GroupService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    // lấy id chuyên mục
    protected $var_focus = array(47);
    protected $var_classified = array(2,10,18);
    protected $var_project = array(34);
    private GroupService $groupService;

    public function __construct()
    {
        $this->groupService = new GroupService;
    }

//-------------------------------------------------------------HOME-------------------------------------------------------------//
    // Seo trang chủ
    public function seo_home()
    {
        $home = HomeConfig::first();

        return view('Admin.Seo.SeoHomeConfig',compact('home'));
    }

    // Lưu seo trang chủ
    public function post_home(Request $request,$id){
        $homeConfig = HomeConfig::first();

        $data = [
            'meta_title' => $request->meta_title,
            'meta_key' =>$request->meta_key,
            'meta_desc'=>$request->meta_desc,
            'home_url' =>$request->home_url,
        ];
        $homeConfig->update($data);

        Cache::forget('home_seo_config');

        # Note log
        // Helper::create_admin_log(5, $data);

        Toastr::success('Cập nhật thành công');
        return back();
    }

//-------------------------------------------------------------CLASSIFIED-------------------------------------------------------------//
    // Danh sách chuyên mục tin đăng
    public function list_classified(Request $request){
    $items = 10;
    if(isset($_GET['items'])){
        $items = $_GET['items'];
    }

    $group = new Collection([]);
    foreach ($this->var_classified as $item){
        $test =$this->groupService->getChildren($item);
        $group = $group->merge($test);
    }
    $parent_group = new Collection();
    $parent_group = $this->groupService->prepareData($group);

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
    return view('Admin.Seo.SeoClassified',compact('group'));
}

    // Seo chuyên mục tin đăng
    public function seo_classified($id){
        $category= Group::findOrFail($id);
        return view('Admin.Seo.EditSeoClassified',compact('category'));
    }

    // Lưu chuyên mục tin đăng
    public function post_classified(Request $request,$id){
        $category = Group::findOrFail($id);

        $data = [
            'meta_title' => $request->meta_title,
            'meta_key' =>$request->meta_key,
            'meta_desc'=>$request->meta_desc,
            'group_url'=>$request->group_url,
        ];
        $category->update($data);

        # Note log
        // Helper::create_admin_log(6, $data);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.seo.listclassified'));
    }

//-----------------------------------------------------------FOCUS NEWS-----------------------------------------------------------//
    // Danh sách chuyên mục tiêu điểm
    public function list_focuscategory(Request $request){
    $items = 10;
    if(isset($_GET['items'])){
        $items = $_GET['items'];
    }

    $group = new Collection([]);
    foreach ($this->var_focus as $item){
        $test =$this->groupService->getChildren($item);
        $group = $group->merge($test);
    }
    $parent_group = new Collection();
    $parent_group = $this->groupService->prepareData($group);

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
    return view('Admin.Seo.SeoFocus',compact('group'));
}

    // Seo chuyên mục tiêu điểm
    public function seo_focus($id){
        $category= Group::findOrFail($id);
        return view('Admin.Seo.EditSeoFocus',compact('category'));
    }

    // Lưu chuyên mục tiêu điểm
    public function post_focus(Request $request,$id){
        $category = Group::findOrFail($id);

        $data = [
            'meta_title' => $request->meta_title,
            'meta_key' =>$request->meta_key,
            'meta_desc'=>$request->meta_desc,
            'group_url'=>$request->group_url,
        ];
        $category->update($data);

        # Note log
        // Helper::create_admin_log(7, $data);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.seo.listfocus'));
    }

//-----------------------------------------------------------PROJECT-----------------------------------------------------------//
    // Danh sách chuyên mục dự án
    public function list_project(Request $request){
    $items = 10;
    if(isset($_GET['items'])){
        $items = $_GET['items'];
    }

    $group = new Collection([]);
    foreach ($this->var_project as $item){
        $test =$this->groupService->getChildren($item);
        $group = $group->merge($test);
    }
    $parent_group = new Collection();
    $parent_group = $this->groupService->prepareData($group);

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
    return view('Admin.Seo.SeoProject',compact('group'));
}

    // Seo chuyên mục dự án
    public function seo_project($id){
        $category= Group::findOrFail($id);
        return view('Admin.Seo.EditSeoProject',compact('category'));
    }

    // Lưu seo chuyên mục dự án
    public function post_project(Request $request,$id){
        $category = Group::findOrFail($id);

        $data = [
            'meta_title' => $request->meta_title,
            'meta_key' =>$request->meta_key,
            'meta_desc'=>$request->meta_desc,
            'group_url'=>$request->group_url,
        ];
        $category->update($data);

        # Note log
        // Helper::create_admin_log(8, $data);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.seo.listproject'));
    }
}
