<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Models\User\UserPostComment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class UserPostCommentController extends Controller
{
    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        UserPostComment::query()
            ->find($ids)
            ->each(function($item) {
                foreach ($item->children()->get() as $child) {
                    $child->delete();
                }
                $item->delete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        UserPostComment::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                foreach ($item->children()->withIsDeleted()->get() as $child) {
                    $child->restore();
                }
                $item->restore();
            });

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        UserPostComment::withIsDeleted()
            ->find($ids)
            ->each(function($item) {
                foreach ($item->children()->withIsDeleted()->get() as $child) {
                    $child->forceDelete();
                }
                $item->forceDelete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }
}
