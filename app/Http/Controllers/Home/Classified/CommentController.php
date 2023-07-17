<?php

namespace App\Http\Controllers\Home\Classified;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classified\AddCommentRequest;
use App\Http\Requests\Classified\UpdateCommentRequest;
use App\Http\Resources\Classified\CommentResource;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedComment;
use App\Services\Classifieds\ClassifiedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private ClassifiedService $classifiedService;

    public function __construct()
    {
        $this->classifiedService = new ClassifiedService;
    }

    public function store(Classified $classified, AddCommentRequest $request)
    {
        $user = Auth::guard('user')->user();
        $this->authorizeForUser($user, 'comment', $classified);

        $data = [
            'content' => $request->content,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
        ];

        $comment = $this->classifiedService->addComment($classified, $data);

        $rating = $classified->ratings()
            ->firstWhere([
                'user_id' => $user->id
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Bình luận thành công',
            'data' => [
                'comment' => (new CommentResource($comment))->resolve([]),
                'user_rating' => data_get($rating, 'star', 0)
            ]
        ], 200);
    }

    public function update(ClassifiedComment $comment, UpdateCommentRequest $request)
    {
        $user = Auth::guard('user')->user();
        $this->authorizeForUser($user, 'update', $comment);

        $data = [
            'content' => $request->content,
        ];

        $comment = $this->classifiedService->updateComment($comment, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => [
                'comment' => (new CommentResource($comment))->resolve([]),
            ]
        ], 200);
    }

    public function like(ClassifiedComment $comment)
    {
        $user = Auth::guard('user')->user();
        $this->authorizeForUser($user, 'like', $comment);

        $result = $this->classifiedService->like($comment, $user);
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

    public function destroy(ClassifiedComment $comment, Request $request)
    {
        $user = Auth::guard('user')->user();
        $limitDeletePerDay = config('constants.classified.comment.limit_delete_per_day', 3);
        $dayDeletes = ClassifiedComment::where('user_id', $user->id)
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
