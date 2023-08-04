<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Models\Classified\ClassifiedComment;
use App\Models\Group;
use App\Models\ProjectComment;
use App\Models\User\UserPostComment;
use App\Services\CommentService;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private CommentService $commentService;
    private UserService $userService;

    public function __construct()
    {
        $this->commentService = new CommentService;
        $this->userService = new UserService;
    }

    //cấm tài khoản
    public function forbidden_account_project($id)
    {
        $comment = ProjectComment::findOrFail($id);
        $user = $comment->user;

        if ($user) {
            $user->isForbidden()
                ? $this->userService->unforbiddenUser($user)
                : $this->userService->forbiddenUser($user);
        }

        Toastr::success('Thành công');
        return redirect(route('admin.comment.list-project'));
    }

    //chặn tài khoản
    public function locked_account_project($id)
    {
        $comment = ProjectComment::findOrFail($id);
        $user = $comment->user;

        if ($user) {
            $user->isBlocked()
                ? $this->userService->unblockUser($user)
                : $this->userService->blockUser($user);
        }

        Toastr::success('Thành công');
        return redirect(route('admin.comment.list-project'));
    }

    //bình luận dự án -------------------------------------------------------------------------

    public function trash_project(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }

        $listQuery = ProjectComment::onlyIsDeleted()
            ->join('user', 'project_comment.user_id', 'user.id')
            ->join('user_detail', 'user_detail.user_id', 'user.id')
            ->join('project', 'project.id', 'project_comment.project_id')
            ->leftJoin('group', 'project.group_id', 'group.id')
            ->leftJoin('group as group_parent', 'group.parent_id', 'group_parent.id')
            ->select(
                'project_comment.id',
                'project_comment.comment_content',
                'project_comment.created_at',
                'user.username',
                'group.group_name',
                'project_comment.user_id as created_by',
                'project.project_url',
                'user.is_forbidden',
                'user.is_locked',
                'group_parent.group_url as group_parent_url',
                'group_parent.group_name as group_parent_name'
            );

        //phân quyền
        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listQuery = $listQuery->join('admin', 'project.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;
            $listQuery = $listQuery->where('project.created_by', $admin_id);
        }
        //phân quyền
        $list = $listQuery->latest('project_comment.id')
            ->paginate($items);

        return view('Admin/Comment/TrashListCommentProject', [
            'list' => $list
        ]);
    }

    #danh sách
    public function list_project(Request $request)
    {
        $items = (int) $request->items ?: 10;
        $status = $request->status ?: 3;

        $category = Group::select('id', 'group_name')
            ->where(['parent_id' => 34])
            ->get();

        $listQuery = ProjectComment::select(
            'project_comment.*',
            'project_comment.id',
            'project_comment.comment_content',
            'project_comment.created_at',
            'user.username',
            'group.group_name',
            'project_comment.user_id as created_by',
            'project.project_url',
            'user.is_forbidden',
            'user.is_locked',
            'user.email',
            'user_detail.fullname',
            'user.phone_number'
        )
            ->with('project', 'user')
            ->leftJoin('user', 'project_comment.user_id', 'user.id')
            ->leftJoin('user_detail', 'user_detail.user_id', 'user.id')
            ->leftJoin('project', 'project.id', 'project_comment.project_id')
            ->leftJoin('group', 'group.id', 'project.group_id');

        //phân quyền
        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listQuery = $listQuery->join('admin', 'project.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;
            $listQuery = $listQuery->where('project.created_by', $admin_id);
        }

        //phân quyền
        if ($request->keyword) {
            $listQuery->where('fullname', 'like', "%$request->keyword%")
                ->orWhere('comment_content', 'like', "%$request->keyword%")
                ->orWhere('user.email', 'like', "%$request->keyword%")
                ->orWhere('user.phone_number', '=', $request->keyword);
        }
        if ($request->from_date) {
            $listQuery->where('project_comment.created_at', '>', date(strtotime($request->from_date)));
        }
        if ($request->to_date) {
            $listQuery->where('project_comment.created_at', '<', date(strtotime($request->to_date)));
        }
        if ($request->phone) {
            $listQuery->where('phone_number', '=', $request->phone);
        }
        if ($request->category) {
            $listQuery->where('group.id', '=', $request->category);
        }
        if ($status == 0) {
            $listQuery
                ->where('user.is_forbidden', '=', 0)
                ->where('user.is_locked', '=', 0);
        }
        if ($status == 1) {
            $listQuery->where('user.is_locked', '=', 1);
        }
        if ($status == 2) {
            $listQuery->where('user.is_forbidden', '=', 1);
        }

        $trashQuery = clone $listQuery;
        $count_trash = $trashQuery->onlyIsDeleted()->count();

        $list = $listQuery->latest('project_comment.id')
            ->paginate($items);

        return view('Admin.Comment.ListCommentProject', [
            'list' => $list,
            'category' => $category,
            'trash_num' => $count_trash,
        ]);
    }

    //Bình luận tin đăng -----------------------------------------------------------------
    //cấm tài khoản
    public function forbidden_account_classified($id)
    {
        $comment = ClassifiedComment::findOrFail($id);
        $user = $comment->user;

        if ($user) {
            $user->isForbidden()
                ? $this->userService->unforbiddenUser($user)
                : $this->userService->forbiddenUser($user);
        }

        Toastr::success('Thành công');
        return redirect(route('admin.comment.list-project'));
    }

    //chặn tài khoản
    public function locked_account_classified($id)
    {
        $comment = ClassifiedComment::findOrFail($id);
        $user = $comment->user;

        if ($user) {
            $user->isBlocked()
                ? $this->userService->unblockUser($user)
                : $this->userService->blockUser($user);
        }

        Toastr::success('Thành công');
        return redirect(route('admin.comment.list-project'));
    }

    public function trash_classified(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }

        // old should improve
        $listQuery = ClassifiedComment::onlyIsDeleted()
            ->select('classified_comment.id', 'classified_comment.comment_content', 'classified_comment.created_at', 'user.username', 'group.group_name', 'classified_comment.user_id as created_by', 'classified.classified_url', 'user.is_forbidden', 'user.is_locked')
            ->leftJoin('user', 'classified_comment.user_id', 'user.id')
            ->leftJoin('user_detail', 'user_detail.user_id', 'user.id')
            ->leftJoin('classified', 'classified.id', 'classified_comment.classified_id')
            ->leftJoin('group', 'group.id', 'classified.group_id');

        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;

            $listQuery = $listQuery->join('admin', 'classified.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } elseif ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;
            $listQuery = $listQuery->where('classified.created_by', $admin_id);
        }

        $list = $listQuery->orderBy('classified_comment.id', 'desc')
            ->paginate($items);

        return view('Admin/Comment/TrashListCommentClassified', [
            'list' => $list
        ]);
    }

    public function list_classified(Request $request)
    {
        $request['admin_role_id'] = Auth::guard('admin')->user()->rol_id;
        $request['admin_id'] = Auth::guard('admin')->user()->id;
        $category = Group::select('id', 'group_name')
            ->where(['parent_id' => 34])
            ->get();
        $countTrash = ClassifiedComment::onlyIsDeleted()->count();
        $classifiedComments = $this->commentService->listClassifiedComments($request->all());

        return view('Admin.Comment.ListCommentClassified', [
            'list' => $classifiedComments,
            'category' => $category,
            'trash_num' => $countTrash,
        ]);
    }

    //Bình luận bài viết -----------------------------------------------------------------
    //cấm tài khoản
    public function forbidden_account_user_post($id)
    {
        $comment = UserPostComment::findOrFail($id);
        $user = $comment->user;

        if ($user) {
            $user->isForbidden()
                ? $this->userService->unforbiddenUser($user)
                : $this->userService->forbiddenUser($user);
        }

        Toastr::success('Thành công');
        return back();
    }

    //chặn tài khoản
    public function locked_account_user_post($id)
    {
        $comment = UserPostComment::findOrFail($id);
        $user = $comment->user;

        if ($user) {
            $user->isBlocked()
                ? $this->userService->unblockUser($user)
                : $this->userService->blockUser($user);
        }

        Toastr::success('Thành công');
        return back();
    }

    public function trash_user_post(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }

        $listQuery = UserPostComment::onlyIsDeleted()
            ->select('user_post_comment.id', 'user_post_comment.comment_content', 'user_post_comment.created_at', 'user.username', 'user_post_comment.user_id as created_by', 'user_post.post_code', 'user.is_forbidden', 'user.is_locked')
            ->leftJoin('user', 'user_post_comment.user_id', 'user.id')
            ->leftJoin('user_detail', 'user_detail.user_id', 'user.id')
            ->leftJoin('user_post', 'user_post.id', 'user_post_comment.user_post_id')
            ->leftJoin('admin', 'user_post.created_by', '=', 'admin.id');

        //phân quyền
        if ($request->request_list_scope == 2) {
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listQuery = $listQuery->join('admin', 'user_post.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;
            $listQuery = $listQuery->where('user_post.created_by', $admin_id);
        }

        //phân quyền
        $list = $listQuery->latest('user_post_comment.id')
            ->paginate($items);

        return view('Admin/Comment/TrashListCommentUserPost', [
            'list' => $list
        ]);
    }

    public function list_user_post(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }
        $status = 3;
        if ($request->has('status')) {
            $status = $request->status;
        }
        $category = Group::select('id', 'group_name')
            ->where(['parent_id' => 34])
            ->get();

        $listQuery = UserPostComment::query()
            ->join('user', 'user_post_comment.user_id', 'user.id')
            ->join('user_detail', 'user_detail.user_id', 'user.id')
            ->join('user_post', 'user_post.id', 'user_post_comment.user_post_id')
            ->select(
                'user_post_comment.id',
                'user_post_comment.comment_content',
                'user_post_comment.created_at',
                'user.username',
                'user_post_comment.user_id as created_by',
                'user_post.post_code',
                'user.is_forbidden',
                'user.is_locked',
                'user.email',
                'user_detail.fullname',
                'user.phone_number'
            );
        //phân quyền
        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listQuery = $listQuery->join('admin', 'user_post.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;
            $listQuery = $listQuery->where('user_post.created_by', $admin_id);
        }

        // filter
        if ($request->keyword) {
            $listQuery->where('user_post_comment.comment_content', 'like', '%' . $request->keyword . '%')
                ->orWhere('fullname', 'like', "%$request->keyword%")
                ->orWhere('user.email', 'like', "%$request->keyword%")
                ->orWhere('phone_number', '=', $request->keyword);
        }
        if ($request->from_date) {
            $listQuery->where('user_post_comment.created_at', '>', date(strtotime($request->from_date)));
        }
        if ($request->to_date) {
            $listQuery->where('user_post_comment.created_at', '<', date(strtotime($request->to_date)));
        }
        if ($request->phone) {
            $listQuery->where('user.phone_number', '=', $request->phone);
        }
        if ($status == 0) {
            $listQuery
                ->where('user.is_forbidden', '=', 0)
                ->where('user.is_locked', '=', 0);
        }
        if ($status == 1) {
            $listQuery->where('user.is_locked', '=', 1);
        }
        if ($status == 2) {
            $listQuery->where('user.is_forbidden', '=', 1);
        }

        $trashQuery = clone $listQuery;

        $list = $listQuery->latest('user_post_comment.id')
            ->paginate($items);

        $count_trash = $trashQuery->onlyIsDeleted()->count();

        return view('Admin/Comment/ListCommentUserPost', [
            'list' => $list,
            'category' => $category,
            'trash_num' => $count_trash,
        ]);
    }
}
