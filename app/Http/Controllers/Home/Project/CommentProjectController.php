<?php

namespace App\Http\Controllers\Home\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Project\CommentProjectRequest;
use App\Models\Project;
use App\Models\ProjectComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentProjectController extends Controller
{
    public function new_comments($id)
    {
        $items = 5;
        $project_id = $id;
        $comments = ProjectComment::query()
            ->with('user_detail:id,fullname,image_url','admin:id,admin_fullname,image_url')
            ->leftJoin('project','project.id','=','project_comment.project_id')
            ->leftJoin('project_rating', function ($leftJoin) {
                $leftJoin->on('project_comment.user_id','=','project_rating.user_id')
                    ->on('project_rating.project_id','=','project.id');
            })->where([
                'project_comment.parent_id' => null,
                'project_comment.is_deleted' => 0,
                'project_comment.project_id' => $project_id,

            ])
            ->orderBy('project_comment.is_new','asc')
            ->orderBy('project_comment.created_at','desc')
            ->select('project_comment.*',
                'project_rating.star')
            ->paginate($items);;

        $user = Auth::guard('admin')->user();
        $parent_comment = ProjectComment::whereIn('id', $comments->pluck('id')->all())->with('children')->get();
        foreach ($parent_comment as $cmt){
            if (count($cmt->children->all()) > 0){
                foreach ($cmt->children as $c){
                    $c->is_new = 0;
                    $c->update();
                }
            }
            $cmt->is_new = 0;
            $cmt->update();
        }
        return view('components.home.project.comment-project', compact('comments', 'user', 'project_id'))->render();
    }

    public function store(CommentProjectRequest $request)
    {
        $user_id = null;
        $admin_id = null;
        $comment = new ProjectComment();
        $comment->fill($request->all());
        if (Auth::guard('admin')->check()) {
            $admin_id = Auth::guard('admin')->id();
            if ($admin_id == Project::find($request->project_id)->created_by)
                $comment->is_new = 0;
        } else
            $user_id = Auth::id();
        $comment->user_id = $user_id;
        $comment->admin_id = $admin_id;
        $comment->created_at = strtotime('now');
        $comment->num_vote = 0;
        $comment->save();

        $user = Auth::guard('admin')->user();
        if ($comment->parent_id)
            return view('components.home.project.children-comment', compact('comment'))->render();
        return view('components.home.project.parent-comment', compact('comment', 'user'))->render();
    }

    public function update(CommentProjectRequest $request)
    {
//        if (Auth::guard('admin')->check())
//            $updated_by = Auth::guard('admin')->id();
//        else
//            $updated_by = Auth::id();
        $comment = ProjectComment::find($request->id);
        $comment->comment_content = $request->comment_content;
//        $comment->updated_by = $updated_by;
        $comment->updated_at = strtotime('now');
        $comment->update();
        return 1;
    }

    public function delete($id)
    {
        $comment = ProjectComment::findOrFail($id);
        if (count($comment->children->all())) {
            foreach ($comment->children as $child) {
                $child->is_deleted = 1;
                $child->update();
            }
        }
        $comment->is_deleted = 1;
        $comment->update();
    }

    // like comment
    public function like_comment($comment_id){
        $admin_id = Auth::guard('admin')->id();

        if (!$admin_id) {
            return response()->json('Vui lòng đăng nhập', 401);
        }
        $value = '';
        if (!DB::table('project_like_comment')->where('admin_id', $admin_id)->where('comment_id', $comment_id)->first()) {
            $value = 'like';
            DB::table('project_like_comment')->insert([
                'admin_id' => $admin_id,
                'comment_id' => $comment_id,
            ]);
            return response()->json($value, 200);
        }
        $value = 'unlike';
        DB::table('project_like_comment')->where([
            'admin_id' => $admin_id,
            'comment_id' => $comment_id,
        ])->delete();
        return response()->json($value, 200);
    }
}
