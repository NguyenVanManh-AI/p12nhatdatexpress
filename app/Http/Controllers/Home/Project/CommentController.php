<?php

namespace App\Http\Controllers\Home\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\CommentRequest;
use App\Http\Requests\Project\UpdateCommentRequest;
use App\Http\Resources\Project\CommentResource;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CommentController extends Controller
{
    private ProjectService $projectService;

    public function __construct()
    {
        $this->projectService = new ProjectService;
    }

    // bình luận dự án
    public function store(Project $project, CommentRequest $request)
    {
        $user = Auth::guard('user')->user();
        $this->authorizeForUser($user, 'comment', $project);

        $data = [
            'content' => $request->content,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
        ];

        $projectComment = $this->projectService->addComment($project, $data);
        $rating = $project->ratings()
            ->firstWhere([
                'user_id' => $user->id
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Bình luận thành công',
            'data' => [
                'comment' => (new CommentResource($projectComment))->resolve([]),
                'user_rating' => data_get($rating, 'star', 0)
            ]
        ], 200);
    }

    public function update(ProjectComment $comment, UpdateCommentRequest $request)
    {
        $user = Auth::guard('user')->user();
        $this->authorizeForUser($user, 'update', $comment);

        $data = [
            'content' => $request->content,
        ];

        $ProjectComment = $this->projectService->updateComment($comment, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => [
                'comment' => (new CommentResource($ProjectComment))->resolve([]),
            ]
        ], 200);
    }

    public function like(ProjectComment $comment)
    {
        $user = Auth::guard('user')->user();
        $this->authorizeForUser($user, 'like', $comment);

        $result = $this->projectService->like($comment, $user);
        $liked = count($result['attached']) > 0;

        return response()->json([
            'success' => true,
            'message' => ($liked ? 'Thích' : 'Bỏ thích') . ' thành công',
            'data' => [
                'likes_count' => $comment->likes->count(),
                'liked' => $liked,
            ]
        ], 200);
    }

    public function destroy(ProjectComment $comment, Request $request)
    {
        $user = Auth::guard('user')->user();
        $limitDeletePerDay = config('constants.classified.comment.limit_delete_per_day', 3);
        $dayDeletes = ProjectComment::where('user_id', $user->id)
            ->onlyIsDeleted()
            ->whereBetWeen('updated_at', [now()->startOfDay()->timestamp, now()->endOfDay()->timestamp])
            ->count();

        if ($dayDeletes >= $limitDeletePerDay) {
            return response()->json([
                'success' => false,
                'message' => "Mỗi tài khoản chỉ được xóa {$limitDeletePerDay} bình luận trên ngày",
            ], 403);
        }

        $comment->children()->delete();
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xoá thành công',
            'data' => [
                'comment' => (new CommentResource($comment))->resolve([]),
            ]
        ], 200);
    }
}
