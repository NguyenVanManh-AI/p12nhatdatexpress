<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Models\ProjectComment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ProjectCommentController extends Controller
{
    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        ProjectComment::query()
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

        ProjectComment::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                foreach ($item->children()->onlyIsDeleted()->get() as $child) {
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

        ProjectComment::withIsDeleted()
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
