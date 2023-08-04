<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\AddProjectRequest;
use App\Models\Admin\ProjectLocation;
use App\Models\Direction;
use App\Models\Group;
use App\Models\Project;
use App\Models\ProjectParam;
use App\Models\ProjectRequest;
use App\Models\Property;
use App\Models\Province;
use App\Models\Unit;
use App\Models\Utility;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ListRequestController extends Controller
{
    protected $id_group = 34;

    public function list_request(Request $request){
        $items = 10;

       // check group
        if($request->request_list_scope == 2){
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $list_request = ProjectRequest::query()
            ->join('admin', 'project_request.confirmed_by', '=', 'admin.id')
            ->where('admin.rol_id', $admin_role_id);
        }
        // check seft
        else if($request->request_list_scope == 3){
            $admin_id = Auth::guard('admin')->user()->id;
            $list_request = ProjectRequest::where('confirmed_by',$admin_id);
        }
        //check all
        else {
            $list_request = ProjectRequest::query();
            // ->select('id','project_name', 'created_at','confirmed_status');
            // $list_request = $list_request->where(['project_request.is_deleted'=>0]);
        }

        // $list_request = ProjectRequest::query();
        //tìm kiếm
        if($request->has('status') && $request->status !="") {
              $list_request->where('confirmed_status',$request->status);
           }

           if( ($request->has('date_start') && $request->date_start!='')||($request->has('date_end')&&$request->date_end!='')  ){

            if($request->date_start==''){

               $start =Carbon::parse($request->date_end);
               $end =Carbon::parse($request->date_end)->addDay(1);
            }
            else if($request->date_end == ''){
                $start =Carbon::parse($request->date_start);
                $end =Carbon::parse($request->date_start)->addDay(1);
            }
            else
            {
                $start =Carbon::parse($request->date_start);
                $end =Carbon::parse($request->date_end)->addDay(1);
            }
            $start = strtotime($start);
            $end = strtotime($end);
            $list_request->whereBetween('project_request.created_at',[$start,$end]);
        }

        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }

        $list_request= $list_request->select('project_request.*', 'province_name', 'district_name', 'ward_name')
            ->join('province', 'province.id', '=', 'province_id')
            ->join('district', 'district.id', '=', 'district_id')
            ->join('ward', 'ward.id', '=', 'ward_id')
            ->orderBy('confirmed_status')
            ->paginate($items);

        $count_trash = ProjectRequest::onlyIsDeleted()->count();
        return view('Admin.Project.ListRequest',compact('list_request','count_trash'));
    }

    public function trash_request(Request $request){
        $items = 10;

        // check group
         if($request->request_list_scope == 2){
             $admin_role_id = Auth::guard('admin')->user()->rol_id;
             $trash_request = ProjectRequest::query()
             ->join('admin', 'project_request.confirmed_by', '=', 'admin.id')
             ->where('admin.rol_id', $admin_role_id);
         }
         // check seft
         else if($request->request_list_scope == 3){
             $admin_id = Auth::guard('admin')->user()->id;
             $trash_request = ProjectRequest::where('confirmed_by',$admin_id);
         }
         //check all
         else {
             $trash_request = ProjectRequest::query();
         }

         if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }

        $trash_request = $trash_request->onlyIsDeleted()
            ->select('project_request.*', 'province_name', 'district_name', 'ward_name')
            ->join('province', 'province.id', '=', 'province_id')
            ->join('district', 'district.id', '=', 'district_id')
            ->join('ward', 'ward.id', '=', 'ward_id')
            ->paginate($items);

        return view('Admin.Project.TrashRequest',compact('trash_request'));

    }
    public function delete_request($id)
    {
        $projectRequest = ProjectRequest::findOrFail($id);
        $projectRequest->delete();
        // Helper::create_admin_log(52,['is_deleted' => 1]);

        Toastr::success('Chuyển vào thùng rác thành công');
        return back();
    }

    public function dont_write_item($id)
    {
        $projectRequest = ProjectRequest::findOrFail($id);

        $projectRequest->update([
            'confirmed_status' =>3,
            'confirmed_by'=>Auth::guard('admin')->user()->id
        ]);

        // Helper::create_admin_log(52,[
        //     'confirmed_status' =>3,
        //     'confirmed_by'=>Auth::guard('admin')->user()->id
        // ]);

        Toastr::success('Cập nhật trạng thái thành công');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        ProjectRequest::query()
            ->find($ids)
            ->each(function($item) {
                $item->delete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        ProjectRequest::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->restore();
            });

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        ProjectRequest::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function write_project(){
        return view('Admin.Project.EditProject');
    }

    // ------- Function write project ---//
//    public function preview(Request $request)
//    {
//        // $project = Project::find(7);
//        // $properties = DB::table('properties')->where('id', '<', 16)->orderBy('id', 'asc')->get(['id','name']);
//        // return view('Admin.Project.ViewProject', compact('project', 'properties'));
//        return view('Admin.Project.ViewProject');
//    }

    //------------------------------------------------------------------------CREATE------------------------------------------------------------------------//
    public function add($id)
    {
        $project = ProjectRequest::findOrFail($id);

        $project->update([
            'confirmed_status' => 1,
            'confirmed_by'=>Auth::guard('admin')->user()->id
        ]);

        $category = Group::select('id', 'group_name')
            ->where('parent_id', $this->id_group)
            ->get();
        $province = Province::showed()->orderBy('province_type', 'desc')->get();
        $direction = Direction::showed()->get(['id', 'direction_name']);
        $unit_rent = Unit::showed()->where(['unit_type' => 'C', ['unit_name', 'like', '%/tháng']])->get(['id', 'unit_code', 'unit_name']);
        $unit_sell = Unit::showed()->where(['unit_type' => 'C'])
            ->whereNotIn('id', $unit_rent->pluck('id')->toArray())
            ->get(['id', 'unit_code', 'unit_name']);
        $legal = ProjectParam::showed()->where('param_type', 'L')->get(['id', 'param_code', 'param_name']);
        $utility = Utility::showed()->get(['id', 'utility_name', 'image_url']);
        $properties = Property::where('properties_type', 1)->orderBy('id', 'asc')->get();

        return view('Admin.Project.WriteProject',
            compact('category', 'province', 'direction', 'unit_sell', 'unit_rent', 'legal', 'utility','project', 'properties'));
    }

    public function store(AddProjectRequest $request,$id)
    {
        // Create a new Project
        $project = new Project();
        $project->fill($request->all());
        $project->bank_sponsor = $request->bank_sponsor ?? 0;
        $project->created_by = Auth::guard('admin')->id();
        $project->created_at = strtotime('now');
        $project->area_unit_id = 7; // dien_tich (m2)
        $project->project_road_unit = 9; //road_unit (m)
        $project->project_unit_id = 8; //quy_mo (ha)
        $project->list_utility = is_array($project->list_utility) ? json_encode(collect($project->list_utility)->keys()) : null;
        // Check have image
        if ($request->has('image_url')) {
            $image_array = [];
            if (json_decode($request->image_url)) {
                $image_array = json_decode($request->image_url);
            } else $image_array[] = $request->image_url;

            if ($request->image_url_order != null) {
                $list_order = json_decode($request->image_url_order);
                $new_image_list = [];
                foreach ($list_order as $value) {
                    $new_image_list[] = $image_array[$value];
                }
                $image_array = $new_image_list;
            }
            $project->image_url = json_encode($image_array);
            $project->image_thumbnail = $image_array[0];
        }
        $project_request = ProjectRequest::find($id);
        if($project_request!=null){
            ProjectRequest::where('id',$id)->update([
                  'confirmed_status' => 2,
                  'confirmed_by'=>Auth::guard('admin')->user()->id
            ]);
        }
        else {
            Toastr::error("Không tồn tại yêu cầu");
            return back();
        }
        // Helper::create_admin_log(55,$project);
        $project->save();
        $projectLocation = new ProjectLocation();
        $projectLocation->fill($request->all());
        $projectLocation->project_id = $project->id;
        $projectLocation->save();

        Toastr::success('Thêm mới dự án thành công');
        return redirect(route('admin.request.list'));
    }
}
