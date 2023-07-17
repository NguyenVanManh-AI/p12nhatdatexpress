<?php

namespace App\Http\Controllers\Admin\Report\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\ReportGroup;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportProjectController extends Controller
{
    public function list(Request $request){
        // get report_group
        $report_group = ReportGroup::showed()->where('type','=',1)->get(); // phân trang
        $items = 10;
        if(isset($_GET['items'])){
            $items = $_GET['items'];
        }
        $list = Project::Wherehas('report_project')
            ->where(function($query) use($request){
                $request->keyword!=''?$query->where('project_name','like','%'.$request->keyword.'%'):null;
            })->where(function($query) use($request){
                // lọc theo nội dung báo cáo
                $request->report_content!=''?$query->WhereRelation('report_project','report_type','=',$request->report_content):null;
                // lọc theo ngày bắt đầu
                if($request->from_date!=''){
                    $from =strtotime(Carbon::parse($request->from_date));
                    $query->WhereRelation('report_project','report_time','>',$from);
                }
                // lọc theo ngày kết thúc
                if($request->to_date!=''){
                    $to =strtotime(Carbon::parse($request->to_date)->addDay(1));
                    $query->WhereRelation('report_project','report_time','<',$to);
                }
            })
            ->paginate($items);

        return view('Admin.Report.Project.report',compact('list','report_group'));
    }
    // báo cáo sai
    public function report_false($id){
        $projectReports = ProjectReport::where('project_id','=',$id)->where('report_position',1)->get();

        if(!$projectReports) {
            Toastr::error("Không bị báo cáo");
            return back();
        }

        // Helper::create_admin_log(134,$data);

        foreach ($projectReports as $report) {
            $report->update([
                'report_result' => 0,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        Toastr::success("Cập nhật báo cáo thành công");
        return back();
    }

    // chặn hiển thị
    public function block_display($id){
        $project = Project::findOrFail($id);

        // tìm các báo cáo
        $project_report = ProjectReport::where('project_id','=',$id)->where('report_position',1)->get();
        if($project_report == null){
            Toastr::error("Không bị báo cáo");
            return back();
        }

        // tìm tin rao báo cáo
        $project->update([
            'is_show' => 0
        ]);
        // Helper::create_admin_log(132,$data);

        // xác nhận trạng thái báo cáo đúng
        foreach ($project_report as $report){
            $report->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        Toastr::success("Chặn hiển thị thành công");
        return back();
    }

    // Khôi phục hiển thị
    public function unblock_display($id){
        $project = Project::findOrFail($id);

        $project->update([
            'is_show' => 1
        ]);
        // Helper::create_admin_log(133,$data);

        Toastr::success("Khôi phục hiển thị thành công");
        return back();
    }

    // xóa dự án
    public function delete($id){
        $project = Project::findOrFail($id);

        $project_report = ProjectReport::where('project_id','=',$id)->where('report_position',1)->get();
        if($project_report == null){
            Toastr::error("Không bị báo cáo");
            return back();
        }

        $project->delete();
        // Helper::create_admin_log(156,$data);

        // xác nhận trạng thái báo cáo đúng
        foreach ($project_report as $item){
            $item->update([
                'report_result' => 1,
                'confirm_status' => 1,
                'confirm_by' => Auth::guard('admin')->user()->id
            ]);
        }

        Toastr::success("Chặn hiển thị thành công");
        return back();
    }
    // Khôi phục dự án
    public function restore($id){
        // tìm tin rao báo cáo
        $project = Project::onlyIsDeleted()->findOrFail($id);
        $project->restore();

        // $data =[
        //     'id'=>$id,
        //     'is_deleted' => 0,
        // ];
        // Helper::create_admin_log(157,$data);

        Toastr::success("Khôi phục thành công");
        return back();
    }
    public function list_action(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        // báo cáo sai
        if($request->action_method == 'report_false'){
            foreach ($request->select_item as $item) {
                $project_report = ProjectReport::where('project_id','=',$item)->where('report_position',1)->get();
                if (!$project_report) continue;

                // Helper::create_admin_log(134,$data);
                foreach ($project as $report){
                    $report->update([
                        'report_result' => 0,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }
            
            Toastr::success("Thành công");
            return back();
        }
        // Chặn hiển thị
        if($request->action_method == 'block_display'){
            foreach ($request->select_item as $item) {
                $project_report = ProjectReport::where('project_id','=',$item)->where('report_position',1)->get();
                if (!$project_report) continue;
                // tìm tin rao báo cáo
                $project = Project::find($item);
                if (!$project) continue;

                $project->update([
                    'is_show' => 0
                ]);
                // Helper::create_admin_log(132,$data);

                // xác nhận trạng thái báo cáo đúng
                foreach ($project_report as $report){
                    $report->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }
            Toastr::success("Thành công");
            return back();
        }
        // Khôi phục hiển thị
        if($request->action_method == 'unblock_display'){
            foreach ($request->select_item as $item) {
                // tìm tin rao báo cáo
                $project = Project::find($item);
                if (!$project) continue;

                $project->update([
                    'is_show' => 1
                ]);
                // Helper::create_admin_log(133,$data);
            }

            Toastr::success("Thành công");
            return back();
        }

        // Xóa dự án
        if($request->action_method == 'delete'){
            foreach ($request->select_item as $item) {
                // tìm các báo cáo
                $project_report = ProjectReport::where('project_id','=',$item)->where('report_position',1)->get();
                if (!$project_report) continue;

                // tìm tin rao báo cáo
                $project = Project::find($item);
                if (!$project) continue;

                $project->delete();

                // Helper::create_admin_log(156,$data);
                foreach ($project_report as $report){
                    $report->update([
                        'report_result' => 1,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }

            Toastr::success("Thành công");
            return back();
        }

        // Khôi phục dự án
        if($request->action_method == 'restore'){
            foreach ($request->select_item as $item) {
                // tìm tin rao báo cáo
                $project = Project::onlyIsDeleted()->find($item);
                if (!$project) continue;

                $project->restore();
                // Helper::create_admin_log(157,$data);
            }

            Toastr::success("Thành công");
            return back();
        }

        return back();
    }
}
