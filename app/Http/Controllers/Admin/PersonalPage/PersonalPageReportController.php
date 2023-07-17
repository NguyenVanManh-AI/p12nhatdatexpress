<?php

namespace App\Http\Controllers\Admin\PersonalPage;

use App\Http\Controllers\Controller;
use App\Models\ReportGroup;
use App\Models\User;
use App\Models\User\UserDetail;
use App\Models\User\UserPost;
use App\Models\User\UserPostComment;
use App\Models\User\UserPostReport;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalPageReportController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    //báo cáo bài viết
    public function list_report_post(Request $request)
    {
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }

        $getReportReason = ReportGroup::where('type', 1)
            ->latest()->get();

        $getListPostQuery = UserPost::query()
            ->join('user', 'user.id', 'user_post.user_id')
            ->join('user_post_report as upr', 'upr.user_post_id', '=', 'user_post.id')
            ->where('upr.report_type', '=', 1)
            ->orderby('upr.report_time', 'desc');

        if ($request->user_post_name) {
            $getListPostQuery->where('user.username', 'like', '%' . $request->user_post_name . '%');
        }
        if ($request->from_date) {
            $getListPostQuery->where('report', '>', date(strtotime($request->from_date)));
        }
        if ($request->to_date) {
            $getListPostQuery->where('report', '<', date(strtotime($request->to_date)));
        }
        if ($request->report_content && $request->report_content != "Tất cả") {
            $getListPostQuery->where('upr.report_content', '=', $request->report_content);
        }
        $getListPost = $getListPostQuery
            ->select('user_post.id', 'user_post.post_content', 'user_post.report', 'user_post.is_show', 'user_post.created_by', 'user.username', 'user.is_forbidden', 'user.is_locked', 'user.id as user_id', 'confirm_status', 'lock_time')
            ->groupBy('user_post.id', 'user_post.post_content', 'user_post.report', 'user_post.is_show', 'user_post.created_by', 'user.username', 'user.is_forbidden', 'user.is_locked', 'user.id', 'confirm_status', 'lock_time')
            ->paginate($items);

        $getListReport = UserPostReport::query()
            ->select('report_content', 'report_position', 'user_post_id', DB::raw('count(report_content) as count'))
            ->groupBy('report_content', 'user_post_id', 'report_position')
            ->get();

        $count_trash = UserPost::onlyIsDeleted()->count();

        return view('Admin.PersonalPage.ListReportPost',
            [
                'getListPost' => $getListPost,
                'getListReport' => $getListReport,
                'getReportReason' => $getReportReason,
                'count_trash' => $count_trash
            ]
        );
    }
    //--------------------------------Thao tác đơn
    //-----------xóa 1 bài viết
    public function delete_post($id)
    {
        $post = UserPost::findOrFail($id);
        $post->delete();

        Toastr::success('Thành công');
        return back();
    }

    //-----------báo cáo sai bài viết
    public function wrong_report_post($id)
    {
        if (isset($id)) {
            UserPostReport::query()
                ->where('user_post_id', $id)
                ->update([
                    'report_result' => 0,
                    'confirm_status' => 1
                ]);

            UserPost::query()
                ->where('id', $id)
                ->update(['report' => null]);
            Toastr::success('Thành công');
        }
        return back();
    }

    public function wrong_report_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $post = UserPost::find($id);
            if (!$post) continue;

            $post->update([
                'report' => null
            ]);

            UserPostReport::query()
                ->where('user_post_id', $id)
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

    //-----------Chặn hiển thị, mở chặn hiển thị
    public function hide_show_post($id)
    {
        $post = UserPost::findOrFail($id);

        $post->update([
            'is_show' => !$post->is_show
        ]);

        Toastr::success(($post->is_show ? 'Mở' : 'Chặn') . ' hiển thị thành công');
        return back();
    }
    //--------------------------------Thao tác list
    //-----------cấm nhiều tài khoản
    public function forbidden_user_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $post = UserPost::find($id);
            if (!$post || !$post->user) continue;

            $this->userService->forbiddenUser($post->user);
        }

        Toastr::success('Cấm tài khoản thành công');
        return back();
    }

    //-----------mở nhiều tài khoản
    public function un_forbidden_user_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $post = UserPost::find($id);
            if (!$post || !$post->user) continue;

            $this->userService->unforbiddenUser($post->user);
        }

        Toastr::success('Mở cấm tài khoản thành công');
        return back();
    }

    public function locked_user_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $post = UserPost::find($id);
            if (!$post || !$post->user) continue;

            $this->userService->blockUser($post->user);
        }

        Toastr::success('Thành công');
        return back();
    }

    public function unlocked_user_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $post = UserPost::find($id);
            if (!$post || !$post->user) continue;

            $this->userService->unblockUser($post->user);
        }

        Toastr::success('Mở cấm tài khoản thành công');
        return back();
    }

    //-----------xóa nhiều bài viết
    public function trash_list_post(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $post = UserPost::find($id);
            if (!$post) continue;
            $post->delete();
        }

        Toastr::success('Chuyển vào thùng rác thành công');
        return back();
    }

    //-------------------------------------------------------Báo cáo bình luận
    //--------------------------------Danh sách báo cáo bình luận
    public function report_comment_post(Request $request)
    {
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        $getReportReasion = ReportGroup::where('type', 2)
            ->orderby('id', 'desc')->get();

        $getListCommentQuery = UserPostComment::query()
            ->join('user', 'user.id', 'user_post_comment.user_id')
            ->join('user_post', 'user_post.id', 'user_post_comment.user_post_id')
            ->join('user_post_report as upr', 'upr.user_post_comment_id', 'user_post_comment.id')
            ->where('upr.report_type', '=', 2)
            ->orderby('upr.report_time', 'desc');
        if ($request->user_post_name) {
            $getListCommentQuery->where('user_post_comment.comment_content', 'like', '%' . $request->user_post_name . '%');
        }
        if ($request->from_date) {
            $getListCommentQuery->where('user_post_comment.report', '>', strtotime($request->from_date));
        }
        if ($request->to_date) {
            $getListCommentQuery->where('user_post_comment.report', '<', strtotime($request->to_date) + 86399);
        }
        if ($request->report_content && $request->report_content != "Tất cả") {
            $getListCommentQuery
                ->where('upr.report_position', '=', 2)
                ->where('upr.report_content', '=', $request->report_content);

        }
        $getListComment = $getListCommentQuery
            ->select('user_post_comment.id', 'user.username', 'user_post_comment.comment_content', 'user_post_comment.is_deleted', 'user_post_comment.report', 'user_post_comment.created_at', 'user_post.user_id as created_by', 'user_post_comment.user_id as user_id', 'user.is_forbidden', 'user.is_locked','user.lock_time', 'upr.confirm_status', 'user_post_comment.is_show')
            ->groupBy('user_post_comment.id', 'user.username', 'user_post_comment.comment_content', 'user_post_comment.is_deleted', 'user_post_comment.report', 'user_post_comment.created_at', 'user_post.user_id', 'user.is_forbidden', 'user.is_locked', 'user.lock_time', 'user_post_comment.user_id', 'upr.confirm_status', 'user_post_comment.is_show')
            ->paginate($items);

        $getListReport = UserPostReport::query()
            ->where('user_post_report.report_position', '=', 2)
            ->select('user_post_comment_id', 'report_content', 'report_position', 'user_post_id', DB::raw('count(report_content) as count'))
            ->groupBy('report_content', 'user_post_id', 'report_position', 'user_post_comment_id')
            ->get();

        $count_trash = UserPostComment::onlyIsDeleted()->count();

        return view('Admin/PersonalPage/ListReportComment',
            [
                'getListComment' => $getListComment,
                'getListReport' => $getListReport,
                'count_trash' => $count_trash,
                'getReportReasion' => $getReportReasion
            ]
        );

    }

    // Chặn / mở hiển thị comment
    public function hide_show_comment($id): RedirectResponse
    {
        $comment = UserPostComment::findOrFail($id);

        $comment->update([
            'is_show' => !$comment->is_show
        ]);

        Toastr::success(($comment->is_show ? 'Mở' : 'Chặn') . ' hiển thị thành công');
        return back();
    }

    //--------------------------------Thao tác đơn
    //-----------báo cáo sai bình luận
    public function wrong_report_comment_post($id)
    {
        $comment = UserPostComment::findOrFail($id);

        $comment->update([
            'report' => null
        ]);

        UserPostReport::query()
            ->where('user_post_comment_id', $id)
            ->each(function ($report) {
                $report->update([
                    'report_result' => 0,
                    'confirm_status' => 1
                ]);
            });

        Toastr::success('Thành công');
        return back();
    }

    //-----------xóa 1 bình luận
    public function trash_item_user_post($id)
    {
        $comment = UserPostComment::findOrFail($id);
        $comment->delete();

        Toastr::success('Chuyển vào thùng rác thành công');
        return back();
    }

    //--------------------------------Thao tác list
    //-----------xóa nhiều bình luận
    public function trash_list_user_post(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $id) {
            $comment = UserPostComment::find($id);
            if (!$comment) continue;

            $comment->delete();
        }

        Toastr::success('Chuyển vào thùng rác thành công');
        return back();
    }

    //-----------cấm nhiều tài khoản bình luận bài viết
    public function forbidden_account_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }


        foreach ($request->select_item as $id) {
            $comment = UserPostComment::find($id);

            if (!$comment || !$comment->user) continue;

            $this->userService->forbiddenUser($comment->user);
        }

        Toastr::success('Cấm tài khoản thành công');
        return back();
    }

    //-----------mở cấm nhiều tài khoản bình luận bài viết
    public function un_forbidden_account_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $comment = UserPostComment::find($id);

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
        foreach ($request->select_item as $id) {
            $comment = UserPostComment::find($id);

            if (!$comment) continue;

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
        }

        Toastr::success('Thành công');
        return back();
    }

    //-----------khóa nhiều tài khoản bình luận bài viết
    public function locked_account_post_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $id) {
            $comment = UserPostComment::find($id);

            if (!$comment || !$comment->user) continue;

            $this->userService->blockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }

    //-----------mở khóa nhiều tài khoản bình luận bài viết
    public function unlocked_account_comment_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $id) {
            $comment = UserPostComment::find($id);

            if (!$comment || !$comment->user) continue;

            $this->userService->unblockUser($comment->user);
        }

        Toastr::success('Thành công');
        return back();
    }

    //-------------------------------------------------------Báo cáo tài khoản
    //--------------------------------Danh sách báo cáo tài khoản
    public function list_report_account(Request $request)
    {
        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        $getReportReasion = ReportGroup::where('type', 3)
            ->orderby('id', 'desc')->get();

        $getListUserQuery = User::query()
            ->join('user_detail', 'user.id', 'user_detail.user_id')
            ->join('user_post_report as upr', 'upr.personal_id', '=', 'user.id')
            ->where('upr.report_type', '=', 3)
            ->orderby('upr.report_time', 'desc');

        if ($request->project_name) {
            $getListUserQuery->where('user.username', 'like', '%' . $request->project_name . '%');
        }
        if ($request->from_date) {
            $getListUserQuery->where('user_detail.report', '>', date(strtotime($request->from_date)));
        }
        if ($request->to_date) {
            $getListUserQuery->where('user_detail.report', '<', date(strtotime($request->to_date)));
        }
        if ($request->report_content && $request->report_content != "Tất cả") {
            $getListUserQuery
                ->where('user_post_report.report_position', '=', 2)
                ->where('user_post_report.report_content', '=', $request->report_content);
        }
        $getListUser = $getListUserQuery
            ->select('user.id', 'user.username', 'user_detail.report', 'user.is_forbidden', 'user.is_locked', 'user.lock_time', 'upr.confirm_status')
            ->groupBy('user.id', 'user.username', 'user_detail.report', 'user.is_forbidden', 'user.is_locked', 'user.lock_time', 'upr.confirm_status')
            ->paginate($items);
        $getListReport = UserPostReport::query()
            ->where('user_post_report.report_position', '=', 3)
            ->select('personal_id', 'report_content', 'report_position', DB::raw('count(report_content) as count'))
            ->groupBy('report_content', 'report_position', 'personal_id')
            ->get();
        return view('Admin.PersonalPage.ListReportAccount',
            [
                'getListUser' => $getListUser,
                'getListReport' => $getListReport,
                'getReportReasion' => $getReportReasion
            ]
        );
    }
    //--------------------------------Thao tác đơn
    //-----------báo cáo sai tài khoản
    public function wrong_report_account($id)
    {
        $detail = UserDetail::findOrFail($id);
        $detail->update([
            'report' => null
        ]);

        UserPostReport::query()
            ->where('personal_id', $id)
            ->each(function ($report) {
                $report->update([
                    'report_result' => 0,
                    'confirm_status' => 1
                ]);
            });

        Toastr::success('Thành công');
        return back();
    }

    //-----------Cấm tài khoản
    public function forbidden_account($id)
    {
        $user = User::findOrFail($id);

        $user->isForbidden()
            ? $this->userService->unforbiddenUser($user)
            : $this->userService->forbiddenUser($user);

        Toastr::success('Thành công');
        return back();
    }

    //-----------Chặn tài khoản
    public function locked_account($id)
    {
        $user = User::findOrFail($id);

        $user->isBlocked()
            ? $this->userService->unblockUser($user)
            : $this->userService->blockUser($user);

        Toastr::success('Thành công');
        return back();
    }

    //--------------------------------Thao tác list
    //-----------cấm nhiều tài khoản
    public function forbidden_account_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $user = User::find($id);

            if (!$user) continue;
            $this->userService->forbiddenUser($user);
        }

        Toastr::success('Cấm tài khoản thành công');
        return back();
    }

    //-----------mở nhiều tài khoản
    public function un_forbidden_account_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $user = User::find($id);

            if (!$user) continue;
            $this->userService->unforbiddenUser($user);
        }

        Toastr::success('Mở cấm tài khoản thành công');
        return back();
    }

    //-----------báo cáo sai nhiều tài khoản
    public function wrong_report_account_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $detail = UserDetail::find($item);
            if (!$detail) continue;

            $detail->update([
                'report' => null
            ]);

            UserPostReport::where('personal_id', $item)
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

    //-----------chặn nhiều tài khoản
    public function locked_account_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $user = User::find($id);

            if (!$user) continue;
            $this->userService->blockUser($user);
        }

        Toastr::success('Thành công');
        return back();
    }

    //-----------mở chặn nhiều tài khoản
    public function un_locked_account_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $user = User::find($id);

            if (!$user) continue;
            $this->userService->unblockUser($user);
        }

        Toastr::success('Thành công');
        return back();
    }
}
