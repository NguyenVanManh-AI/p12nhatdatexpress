<?php

namespace App\Http\Controllers\Admin\StaticPage;

use App\Enums\AdminActionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateStaticPageRequest;
use App\Http\Requests\Admin\UpdateStaticPageRequest;
use App\Services\Admins\StaticPageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPageController extends Controller
{
    private StaticPageService $staticPageService;

    public function __construct()
    {
        $this->staticPageService = new StaticPageService;
    }

    public function index(Request $request)
    {
        $staticPages = $this->staticPageService->index($request);

        $trashCountRequest = new Request([
            'request_list_scope' => $request->request_trash_list_scope
        ]);
        $trashCount = $this->staticPageService->index($trashCountRequest, 'only')->total();

        return view('admin.static-pages.index', [
            'staticPages' => $staticPages,
            'countTrash' => $trashCount
        ]);
    }

    public function trash(Request $request)
    {
        $staticPages = $this->staticPageService->index($request, 'only');

        return view('admin.static-pages.trash', [
            'staticPages' => $staticPages,
        ]);
    }

    public function create()
    {
        return view('admin.static-pages.create');
    }

    public function store(CreateStaticPageRequest $request)
    {
        $this->staticPageService->create($request);

        Toastr::success('Thêm thành công');
        return redirect()->route('admin.static.page.index');
    }

    public function edit(Request $request, $id)
    {
        $staticPage = $this->staticPageService
            ->getPermissionQuery($request)
            ->findOrFail($id);
//            $parent_group = DB::table('static_page_group')->select('id', 'group_title')
//                ->where(['is_show' => 1, 'is_deleted' => 0])
//                ->get();
        return view('admin.static-pages.edit', [
            'item' => $staticPage
        ]);
    }

    public function update(UpdateStaticPageRequest $request, int $id)
    {
        $staticPage = $this->staticPageService
            ->getPermissionQuery($request)
            ->findOrFail($id);

        $staticPage->update([
            'page_title' => $request->page_title,
            'image_url' => $request->image_url,
            'page_description' => $request->page_description,
            'page_content' => $request->page_content,
            'is_highlight' => $request->is_highlight,
//            'page_group' => $request->page_group,
            'page_url' => $request->page_url,
            'meta_title' => $request->meta_title,
            'meta_key' => $request->meta_key,
            'meta_desc' => $request->meta_desc,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => strtotime('now'),
        ]);

        # Note log
        // Helper::create_admin_log(11, $data);

        Toastr::success('Cập nhật thành công');
        return back();
    }

    public function duplicateMultiple(Request $request)
    {
        $result = $this->staticPageService->action($request, AdminActionEnum::DUPLICATE);

        $result
            ? Toastr::success('Nhân bản thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $result = $this->staticPageService->action($request, AdminActionEnum::DELETE);

        $result
            ? Toastr::success('Chuyển vào thùng rác thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $result = $this->staticPageService->action($request, AdminActionEnum::RESTORE);

        $result
            ? Toastr::success('Khôi phục thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $result = $this->staticPageService->action($request, AdminActionEnum::FORCE_DELETE);

        $result
            ? Toastr::success('Xóa thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function updateOrderMultiple(Request $request)
    {
        [$actionRequest, $ids, $trashed] = getActionsParams($request, AdminActionEnum::UPDATE);

        foreach ($ids as $id) {
            $order = $request->show_order[$id];
            $item = $this->staticPageService
                ->getPermissionQuery($actionRequest, $trashed)
                ->find($id);

            if ($item)
                $item->update([
                    'show_order' => $order,
                    'updated_at' => time(),
                    'updated_by' => Auth::guard('admin')->user()->id
                ]);
        }

        Toastr::success('Cập nhật thành công');
        return back();
    }
}
