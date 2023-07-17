<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\EditPropertiesRequest;
use App\Models\AdminConfig;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\ProjectConfig;
use App\Models\ProjectReport;
use App\Models\Property;
use App\Models\ReportGroup;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class ProjectReportController extends Controller
{
    private UserService $userService;

    /**
     * inject UserService into UserController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }

    //ẩn tin tự án
    public function block_display_project_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        //nếu có các bình luận được chọn
        foreach ($request->select_item as $item) {
            $project = Project::find($item);
            if (!$project) continue;

            $project->update([
                'is_show' => 0
            ]);

            // Helper::create_admin_log(132, $data);
        }

        Toastr::success('Chặn hiển thị thành công');
        return back();
    }

    //hiển thị tin dự án
    public function show_display_project_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $project = Project::find($item);
            if (!$project) continue;

            $project->update([
                'is_show' => 1
            ]);
            // Helper::create_admin_log(133, $data);
        }

        Toastr::success('Hiển thị thành công');
        return back();
    }

    //-----------cấm nhiều tài khoản bình luận dự án
    public function forbidden_account_project_list(Request $request)
    {
        //nếu không có dự án nào được chọn
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        //nếu có các bình luận được chọn
        foreach ($request->select_item as $id) {
            $comment = ProjectComment::find($id);

            if (!$comment || !$comment->user) continue;

            $this->userService->forbiddenUser($comment->user);
        }

        Toastr::success('Cấm tài khoản thành công');
        return back();
    }

    //-----------mở cấm nhiều tài khoản bình luận dự án
    public function un_forbidden_account_project_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        //nếu có các bình luận được chọn
        foreach ($request->select_item as $id) {
            $comment = ProjectComment::find($id);
            if (!$comment || !$comment->user) continue;

            $this->userService->unforbiddenUser($comment->user);
        }

        Toastr::success('Mở cấm tài khoản thành công');
        return back();
    }

    //-----------khóa nhiều tài khoản bình luận dự án
    public function locked_account_project_list(Request $request)
    {
        //nếu không có dự án nào được chọn
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        //nếu có các bình luận được chọn
        foreach ($request->select_item as $id) {
            //chuyển user sang trạng thái cấm
            $comment = ProjectComment::find($id);

            if (!$comment || !$comment->user) continue;

            $this->userService->blockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }

    //-----------Báo cáo sai nhiều dự án
    public function wrong_project_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            ProjectReport::where('project_id', $item)
                ->each(function ($report) {
                    $report->update([
                        'report_result' => 0,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id
                    ]);
                });

            // $data = ['id' => $item];
            // Helper::create_admin_log(134, $data);
        }

        Toastr::success('Thành công');
        return back();
    }

    //-----------báo cáo sai nhiều bình luận dự án
    public function wrong_report_comment_project_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            //chuyển trang thái report_result sang
            $projectComment = ProjectComment::find($item);

            if (!$projectComment) continue;

            $projectComment->update([
                'report' => null
            ]);
            
            ProjectReport::where('project_comment_id', $item)
                ->each(function ($report) {
                    $report->update([
                        'report_result' => 0,
                        'confirm_status' => 1,
                        'confirm_by' => Auth::guard('admin')->user()->id
                    ]);
                });
            // Helper::create_admin_log(135, $data);
        }

        Toastr::success('Thành công');
        return back();
    }

    //danh sách dự án bị báo cáo
    public function list_report(Request $request)
    {
        // phân trang
        $items = 10;
        if (isset($_GET['items'])) {
            $items = $_GET['items'];
        }
        $list = Project::query()
            ->when($request->project_id, function ($query, $projectId) {
                return $query->where('id', $projectId);
            });

        if ($request->keyword) {
            $list->where('project_name', 'like', "%$request->keyword%");
        }

        if ($request->report_content) {
            $list->WhereRelation('report_project', 'report_type', '=', $request->report_content);
        }

        if ($request->from_date != '') {
            $from = strtotime(Carbon::parse($request->from_date));
            $list->WhereRelation('report_project', 'report_time', '>', $from);
        }

        if ($request->to_date != '') {
            $to = strtotime(Carbon::parse($request->to_date)->addDay(1));
            $list->WhereRelation('report_project', 'report_time', '<', $to);
        }

        $list = $list->whereHas('report_project')
            ->orWhereHas('report_comment_project')
            ->with('report_project', 'report_comment_project')
            ->paginate($items);

        // get report_group
        $report_group = ReportGroup::showed()->whereIn('type', [1, 2])->get();
        return view('Admin.Project.ListReport', compact('list', 'report_group'));
    }

    //danh sách bình luận dự án bị report
    public function list_report_comment($id, Request $request)
    {
        $items = 10;
        if (isset($_GET['items'])) {
            $items = $_GET['items'];
        }
        $list = ProjectComment::Wherehas('report_comment_project')
            ->where('project_id', $id)
            ->where(function ($query) use ($request) {
                $request->keyword != '' ? $query->WhereRelation('user', 'username', 'like', '%' . $request->keyword . '%') : null;
                $request->keyword != '' ? $query->orWhereRelation('user_detail', 'phone_number', 'like', '%' . $request->keyword . '%') : null;
                $request->keyword != '' ? $query->orWhereRelation('user', 'email', 'like', '%' . $request->keyword . '%') : null;
            })->where(function ($query) use ($request) {
                // lọc theo nội dung báo cáo
                $request->report_content != '' ? $query->WhereRelation('report_comment_project', 'report_type', '=', $request->report_content) : null;
                // lọc theo ngày bắt đầu
                if ($request->from_date != '') {
                    $from = strtotime(Carbon::parse($request->from_date));
                    $query->WhereRelation('report_comment_project', 'report_time', '>', $from);
                }
                // lọc theo ngày kết thúc
                if ($request->to_date != '') {
                    $to = strtotime(Carbon::parse($request->to_date)->addDay(1));
                    $query->WhereRelation('report_comment_project', 'report_time', '<', $to);
                }
            })
            ->paginate($items);
        // get report_group
        $report_group = ReportGroup::showed()->where('type', '=', 2)->get();
        return view('Admin.Project.ListReportComment', compact('list', 'report_group'));
    }

    //chặn hiển thị, ẩn hiển thị tin dự án
    public function hide_show_project($id)
    {
        $project = Project::findOrFail($id);


        $project->update([
            'is_show' => !$project->is_show
        ]);
        // Helper::create_admin_log(132, $data);

        Toastr::success(($project->is_show ? 'Hiển thị' : 'Ẩn') . ' thành công');
        return back();
    }

    //xóa 1 dự án
    public function delete_project($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        // Helper::create_admin_log(130, $data);

        Toastr::success('Xóa thành công');
        return back();
    }

    //khôi phục 1 dự án
    public function restore_project($id)
    {
        $project = Project::onlyIsDeleted()->findOrFail($id);
        $project->restore();
        // Helper::create_admin_log(131, $data);

        Toastr::success('Xóa thành công');
        return back();
    }

    //khôi phục nhiều dự án
    public function restore_list_project(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $project = Project::find($item);
            if (!$project) continue;

            $project->delete();
            // Helper::create_admin_log(131, $data);
        }

        Toastr::success('Thành công');
        return back();
    }

    //xóa nhiều dự án
    public function delete_project_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $project = Project::onlyIsDeleted()->find($item);
            if (!$project) continue;

            $project->restore();
            // Helper::create_admin_log(130, $data);
        }

        Toastr::success('Thành công');
        return back();
    }

    //báo cáo sai 1 dự án (sẽ unactive các báo cáo, xme như dự án chưa bị báo cáo)
    public function wrong_project($id)
    {
        $project = Project::findOrFail($id);

        ProjectReport::where('project_id', $project->id)
            ->each(function ($report) {
                $report->update([
                    'report_result' => 0,
                    'confirm_status' => 1
                ]);
                // Helper::create_admin_log(134, $data);
            });

        Toastr::success('Thành công');
        return back();
    }

    //hàm hiển thị giao diện càu đặt dự án
    public function project_setting()
    {
        $getValue = AdminConfig::firstWhere('config_code', 'C004');
        $getIcon = ProjectConfig::where('type_config', '1')->get();

        return view('Admin/Project/ProjectSetting', [
                'getValue' => $getValue,
                'getIcon' => $getIcon
            ]
        );
    }

    public function post_project_setting(Request $request)
    {
        $validate = $request->validate([
            'ngattrang' => 'required|integer|between:1,255',
            'icon1' => 'max:255',
            'icon2' => 'max:255',
            'icon3' => 'max:255',
            'icon4' => 'max:255',
            'icon5' => 'max:255',
            'icon6' => 'max:255',
        ]);

        $adminConfig = AdminConfig::firstWhere('config_code', 'C004');

        if ($adminConfig) {
            $adminConfig->update([
                'config_value' => $request->ngattrang
            ]);
        }

        for ($i = 1; $i <= 6; $i++) {
            $image = 'icon' . $i;
            $projectConfig = ProjectConfig::where('type_config', '=', '1')->find($i);
            if (!$projectConfig) continue;

            $projectConfig->update([
                'image' => $request->$image
            ]);

            // Helper::create_admin_log(137, $data);
        }

        Toastr::success('Cập nhật thành công');
        return back();
    }

    //hàm giao diện chỉnh sửa thông tin cấu hình dự án
    public function project_properties()
    {
        return view('Admin/Project/ProjectProperties', [
            'getProperties' => Property::get()
        ]);
    }

    //hàm submit chỉnh sửa cấu hình dự án
    public function post_project_properties(EditPropertiesRequest $request)
    {
        // should improve it
        $mapProperties = [
            'tenduan',
            'giaban',
            'huongnha',
            'mohinh',
            'giathue',
            'quymo',
            'chudautu',
            'dientich',
            'hotrovay',
            'vitri',
            'phaply',
            'duong',
            'tinhtrang',
            'noithat',
            'dangngay',
        ];

        foreach ($mapProperties as $key => $name) {
            $property = Property::find($key + 1);
            if (!$property) continue;

            $property->update([
                'name' => $request->$name ?? '',
            ]);
        }

        // old code
        //lấy ra các trường để chỉnh sửa
        // Property::where('id', '=', '1')->limit(1)->update(['name' => $request->tenduan]);
        // Property::where('id', '=', '2')->limit(1)->update(['name' => $request->giaban]);
        // Property::where('id', '=', '3')->limit(1)->update(['name' => $request->huongnha]);
        // Property::where('id', '=', '4')->limit(1)->update(['name' => $request->mohinh]);
        // Property::where('id', '=', '5')->limit(1)->update(['name' => $request->giathue]);
        // Property::where('id', '=', '6')->limit(1)->update(['name' => $request->quymo]);
        // Property::where('id', '=', '7')->limit(1)->update(['name' => $request->chudautu]);
        // Property::where('id', '=', '8')->limit(1)->update(['name' => $request->dientich]);
        // Property::where('id', '=', '9')->limit(1)->update(['name' => $request->hotrovay]);
        // Property::where('id', '=', '10')->limit(1)->update(['name' => $request->vitri]);
        // Property::where('id', '=', '11')->limit(1)->update(['name' => $request->phaply]);
        // Property::where('id', '=', '12')->limit(1)->update(['name' => $request->duong]);
        // Property::where('id', '=', '13')->limit(1)->update(['name' => $request->tinhtrang]);
        // Property::where('id', '=', '14')->limit(1)->update(['name' => $request->noithat]);
        // Property::where('id', '=', '15')->limit(1)->update(['name' => $request->dangngay]);

        Toastr::success('Cập nhật thành công');
        return back();
    }
}
