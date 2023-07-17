<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Models\Classified\ClassifiedComment;
use App\Models\Classified\ClassifiedReport;
use App\Models\ProjectComment;
use App\Models\ProjectReport;
use App\Models\ReportGroup;
use App\Models\User\UserPostComment;
use App\Models\User\UserPostReport;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCommentController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    //--------------------------------------------------------------------tin đăng
    //--------------------------------Danh sách báo cáo
    public function report_comment_post(Request $request){
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        $getReportReasion = ReportGroup::where('type',2)
            ->orderby('id', 'desc')->get();

        $getListCommentQuery = UserPostComment::query()
        ->join('user', 'user.id', 'user_post_comment.user_id')
        ->join('user_detail', 'user.id', 'user_detail.user_id')
        ->join('user_post', 'user_post.id', 'user_post_comment.user_post_id');
        // ->where('user_post_comment.report', '!=', null)
        // ->orderby('user_post_comment.report', 'desc');
        if ($request->user_post_name) {
            $getListCommentQuery
                ->where('user_post_comment.comment_content', 'like', '%' . $request->user_post_name . '%')
                ->orWhere('fullname', 'like', "%$request->user_post_name%")
                ->orWhere('user.email', 'like', "%$request->user_post_name%")
                ->orWhere('phone_number', '=', $request->user_post_name);
        }
        if ($request->from_date) {
            $getListCommentQuery->where('user_post_comment.report', '>', date(strtotime($request->from_date)));
        }
        if ($request->to_date) {
            $getListCommentQuery->where('user_post_comment.report', '<', date(strtotime($request->to_date)));
        }
        if ($request->report_content && $request->report_content != "Tất cả") {
            $getListCommentQuery->join('user_post_report', 'user_post_report.user_post_comment_id', 'user_post_comment.id')
            ->where('user_post_report.report_position', '=', 2)
            ->where('user_post_report.report_content', '=', $request->report_content);
        }

        $getListCommentQuery->join('user_post_report as upr', 'upr.user_post_comment_id', '=', 'user_post_comment.id');

        $getListComment = $getListCommentQuery
        ->select('user_post_comment.id', 'user.username', 'user_post_comment.comment_content','user_post_comment.report', 'user_post_comment.created_at', 'user_post.user_id as created_by', 'user_post_comment.user_id as user_id', 'user.is_forbidden', 'user.is_locked')
        ->whereNotNull('user_post_comment.report')
        ->groupBy('user_post_comment.id', 'user.username', 'user_post_comment.comment_content', 'user_post_comment.report', 'user_post_comment.created_at', 'user_post.user_id', 'user.is_forbidden', 'user.is_locked', 'user_post_comment.user_id')
        ->paginate($items);
        $getListReport = UserPostReport::query()
        ->where('user_post_report.report_position', '=', '2')
        ->select('user_post_comment_id', 'report_content', 'report_position', 'user_post_id', DB::raw('count(report_content) as count'))
        ->groupBy('report_content', 'user_post_id', 'report_position', 'user_post_comment_id')
        ->get();
        $count_trash = UserPostComment::onlyIsDeleted()->count();
        return view('Admin/Comment/ReportCommentUserPost',
            [
                'getListComment' => $getListComment,
                'getListReport' => $getListReport,
                'count_trash' => $count_trash,
                'getReportReasion' => $getReportReasion
            ]
        );

    }
    //--------------------------------Thao tác đơn
    //-----------báo cáo sai bình luận bài viết
    public function wrong_report_comment_post($id)
    {
        $comment = UserPostComment::findOrFail($id);

        $comment->update([
            'report' => null
        ]);

        UserPostReport::where('user_post_comment_id', $id)
            ->each(function ($report) {
                $report->update([
                    'report_result' => 0,
                    'confirm_status' => 1
                ]);
            });

        Toastr::success('Thành công');
        return back();
    }
    //--------------------------------Thao tác list
    //-----------cấm nhiều tài khoản bình luận bài viết
    public function forbidden_account_post_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $comment = UserPostComment::find($item);
            if (!$comment || !$comment->user) continue;

            $this->userService->forbiddenUser($comment->user);
        }

        Toastr::success('Cấm tài khoản thành công');
        return back();
    }
    //-----------mở cấm nhiều tài khoản bình luận bài viết
    public function un_forbidden_account_post_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $comment = UserPostComment::find($item);
            if (!$comment || !$comment->user) continue;

            $this->userService->unforbiddenUser($comment->user);
        }

        Toastr::success('Mở cấm tài khoản thành công');
        return back();
    }
    //-----------báo cáo sai nhiều bình luận bài viết
    public function wrong_report_comment_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $comment = UserPostComment::find($item);

            if (!$comment) continue;

            UserPostReport::where('user_post_comment_id', $item)
                ->each(function ($report) {
                    $report->update([
                        'report_result' => 0,
                        'confirm_status' => 1
                    ]);
                });
        }
        Toastr::success('Thành công');
        return back();
    }

    #-----------khóa nhiều tài khoản bình luận bài viết------------
    public function locked_account_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $comment = UserPostComment::find($item);
            if (!$comment || !$comment->user) continue;

            $this->userService->blockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }
    #mở khóa nhiều tài khoản bình luận bài viết
    public function un_locked_account_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $comment = UserPostComment::find($item);
            if (!$comment || !$comment->user) continue;

            $this->userService->unblockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }

    //--------------------------------------------------------------------tin đăng
    //--------------------------------Danh sách báo cáo
    public function report_comment_classified(Request $request){
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        $getReportReasion = ReportGroup::where('type',2)
        ->orderby('id', 'desc')->get();

        $getListCommentQuery = ClassifiedComment::query()
        ->join('user', 'user.id', 'classified_comment.user_id')
        ->join('user_detail', 'user.id', 'user_detail.user_id')
        ->join('classified', 'classified.id', 'classified_comment.classified_id');
        // ->where('user_post_comment.report', '!=', null)

        if ($request->classified_name) {
            $getListCommentQuery->where('classified_comment.comment_content', 'like', '%' . $request->classified_name . '%')
                ->orWhere('fullname', 'like', "%$request->classified_name%")
                ->orWhere('user.email', 'like', "%$request->classified_name%")
                ->orWhere('phone_number', '=', $request->classified_name);
        }
        if ($request->from_date) {
            $getListCommentQuery->where('classified_comment.report', '>', date(strtotime($request->from_date)));
        }
        if ($request->to_date) {
            $getListCommentQuery->where('classified_comment.report', '<', date(strtotime($request->to_date)));
        }
        if ($request->report_content && $request->report_content != "Tất cả") {
            $getListCommentQuery->join('classified_report', 'classified_report.classified_comment_id', 'classified_comment.id')
            ->where('classified_report.report_position', '=', 1)
            ->where('classified_report.report_content', '=', $request->report_content);
        }
        $getListComment = $getListCommentQuery
        ->select('classified_comment.id', 'user.username', 'classified_comment.comment_content', 'classified_comment.report', 'classified_comment.created_at', 'classified.user_id as created_by', 'classified_comment.user_id as user_id', 'user.is_forbidden', 'user.is_locked')
        ->whereNotNull('classified_comment.report')
        ->groupBy('classified_comment.id', 'user.username', 'classified_comment.comment_content', 'classified_comment.report', 'classified_comment.created_at', 'classified.user_id', 'user.is_forbidden', 'user.is_locked', 'classified_comment.user_id')
        ->paginate($items);
        $getListReport = ClassifiedReport::query()
        ->where('classified_report.report_position', '=', '1')
        ->select('classified_comment_id', 'report_content', 'report_position', 'classified_id', DB::raw('count(report_content) as count'))
        ->groupBy('report_content', 'classified_id', 'report_position', 'classified_comment_id')
        ->get();
        $count_trash = ClassifiedComment::onlyIsDeleted()->count();
        return view('Admin/Comment/ReportCommentClassified',
            [
                'getListComment' => $getListComment,
                'getListReport' => $getListReport,
                'count_trash' => $count_trash,
                'getReportReasion' => $getReportReasion
            ]
        );
    }
    //--------------------------------Thao tác đơn
    //-----------báo cáo sai bình luận tin đăng
    public function wrong_report_comment_classified($id)
    {
        $comment = ClassifiedComment::find($id);

        $comment->update([
            'report' => null
        ]);

        ClassifiedReport::where('classified_comment_id', $id)
            ->each(function ($report) {
                $report->update([
                    'report_result' => 0,
                    'confirm_status' => 1
                ]);
            });

        Toastr::success('Thành công');
        return back();
    }
    //--------------------------------Thao tác list
    //-----------cấm nhiều tài khoản bình luận tin đăng
    public function forbidden_account_classified_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $comment = ClassifiedComment::find($item);

            if (!$comment || !$comment->user) continue;

            $this->userService->forbiddenUser($comment->user);
        }

        Toastr::success('Cấm tài khoản thành công');
        return back();
    }
    //-----------mở cấm nhiều tài khoản bình luận tin đăng
    public function un_forbidden_account_classified_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $comment = ClassifiedComment::find($item);

            if (!$comment || !$comment->user) continue;

            $this->userService->unforbiddenUser($comment->user);
        }

        Toastr::success('Mở cấm tài khoản thành công');
        return back();
    }
    //-----------báo cáo sai nhiều bình luận tin đăng
    public function wrong_report_comment_classified_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $comment = ClassifiedComment::find($item);

            if (!$comment) continue;

            $comment->update([
                'report' => null
            ]);

            ClassifiedReport::where('classified_comment_id', $item)
                ->each(function ($report) {
                    $report->update([
                        'report_result' => 0,
                        'confirm_status' => 1
                    ]);
                });
        }

        Toastr::success('Thành công');
        return back();
    }

    //-----------khóa nhiều tài khoản bình luận tin đăng
    public function locked_account_classified_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $comment = ClassifiedComment::find($item);

            if (!$comment || !$comment->user) continue;

            $this->userService->blockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }
    #mở khóa nhiều tài khoản tin đăng
    public function un_locked_account_classified_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
           $comment = ClassifiedComment::find($item);

            if (!$comment || !$comment->user) continue;

            $this->userService->unBlockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }

    //--------------------------------------------------------------------dự án
    //--------------------------------Danh sách báo cáo
    public function report_comment_project(Request $request){
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        $getReportReasion = ReportGroup::where('type', 1)
        ->orderby('id', 'desc')->get();
        $getListCommentQuery = ProjectComment::query()
        ->join('user', 'user.id', 'project_comment.user_id')
        ->join('user_detail', 'user.id', 'user_detail.user_id')
        ->join('project', 'project.id', 'project_comment.project_id')
        ->where('project_comment.report', '!=', null)
        ->orderby('project_comment.report', 'desc');
        if ($request->project_name) {
            $getListCommentQuery->where('project_comment.comment_content', 'like', '%' . $request->project_name . '%')
                ->orWhere('fullname', 'like', "%$request->project_name%")
                ->orWhere('user.email', 'like', "%$request->project_name%")
                ->orWhere('phone_number', '=', $request->project_name);
        }
        if ($request->from_date) {
            $getListCommentQuery->where('project_comment.report', '>', date(strtotime($request->from_date)));
        }
        if ($request->to_date) {
            $getListCommentQuery->where('project_comment.report', '<', date(strtotime($request->to_date)));
        }
        if ($request->report_content && $request->report_content != "Tất cả") {
            $getListCommentQuery->join('project_report', 'project_report.project_comment_id', 'project_comment.id')
            ->where('project_report.report_position', '=', 1)
            ->where('project_report.report_content', '=', $request->report_content);
        }
        $getListComment = $getListCommentQuery
        ->select('project_comment.*', 'user.username', 'project_comment.comment_content', 'project_comment.report', 'project_comment.created_at', 'project.created_by', 'project_comment.user_id as user_id', 'user.is_forbidden', 'user.is_locked')
        ->groupBy('project_comment.id', 'user.username', 'project_comment.comment_content', 'project_comment.report', 'project_comment.created_at', 'project.created_by', 'user.is_forbidden', 'user.is_locked', 'project_comment.user_id')
        ->paginate($items);
        $getListReport = ProjectReport::query()
        ->where('project_report.report_position', '=', '1')
        ->select('project_comment_id', 'report_content', 'report_position', 'project_id', DB::raw('count(report_content) as count'))
        ->groupBy('report_content', 'project_id', 'report_position', 'project_comment_id')
        ->get();
        $count_trash = ProjectComment::onlyIsDeleted()->count();
        return view('Admin/Comment/ReportCommentProject',
            [
                'getListComment' => $getListComment,
                'getListReport' => $getListReport,
                'count_trash' => $count_trash,
                'getReportReasion' => $getReportReasion
            ]
        );
    }
    //--------------------------------Thao tác đơn
    //-----------báo cáo sai dự án
    public function wrong_report_comment_project($id)
    {
        $comment = ProjectComment::find($id);

        $comment->update([
            'report' => null
        ]);

        ProjectReport::where('project_comment_id', $id)
            ->each(function ($report) {
                $report->update([
                    'report_result' => 0,
                    'confirm_status' => 1
                ]);
            });

        Toastr::success('Thành công');
        return back();
    }
    //--------------------------------Thao tác list
    //-----------cấm nhiều tài khoản bình luận dự án
    public function forbidden_account_project_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $comment = ProjectComment::find($item);
            if (!$comment || !$comment->user) continue;

            $this->userService->forbiddenUser($comment->user);
        }

        Toastr::success('Cấm tài khoản thành công');
        return back();
    }
    //-----------mở cấm nhiều tài khoản bình luận dự án
    public function un_forbidden_account_project_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $comment = ProjectComment::find($item);
            if (!$comment || !$comment->user) continue;

            $this->userService->unforbiddenUser($comment->user);
        }

        Toastr::success('Mở cấm tài khoản thành công');
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
            $comment = ProjectComment::find($item);

            if (!$comment) continue;

            $comment->update([
                'report' => null
            ]);

            ClassifiedReport::where('project_comment_id', $item)
                ->each(function ($report) {
                    $report->update([
                        'report_result' => 0,
                        'confirm_status' => 1
                    ]);
                });
        }
        Toastr::success('Thành công');
        return back();
    }

    //-----------khóa nhiều tài khoản bình luận dự án
    public function locked_account_project_list(Request $request){

        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $comment = ProjectComment::find($item);
            if (!$comment || !$comment->user) continue;

            $this->userService->blockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }

    #mở khóa nhiều tài khoản
    public function un_locked_account_project_list(Request $request){

        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $comment = ProjectComment::find($item);
            if (!$comment || !$comment->user) continue;

            $this->userService->unblockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }
}
