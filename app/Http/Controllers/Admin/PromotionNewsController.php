<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminActionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromotionNews\CreateRequest;
use App\Http\Requests\Admin\PromotionNews\UpdateRequest;
use App\Models\PromotionNew;
use App\Services\Admins\PromotionNewsService;
use App\Services\Admins\PromotionService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionNewsController extends Controller
{
    private PromotionNewsService $promotionNewsService;
    private PromotionService $promotionService;

    public function __construct()
    {
        $this->promotionNewsService = new PromotionNewsService;
        $this->promotionService = new PromotionService;
    }

    public function index(Request $request)
    {
        $news = $this->promotionNewsService->index($request);

        $trashCountRequest = new Request([
            'request_list_scope' => $request->request_trash_list_scope
        ]);
        $trashCount = $this->promotionNewsService->index($trashCountRequest, 'only')->total();

        return view('admin.promotion-news.index', [
            'news' => $news,
            'countTrash' => $trashCount
        ]);
    }

    public function trash(Request $request)
    {
        $news = $this->promotionNewsService->index($request, 'only');

        return view('admin.promotion-news.trash', [
            'news' => $news,
        ]);
    }

    public function create()
    {
        $list_code_0 = $this->promotionService->getListCode();

        return view('admin.promotion-news.create', [
            'promotion' => new PromotionNew(),
            'list_code_0' => $list_code_0
        ]);
    }

    public function store(CreateRequest $request)
    {
        $this->promotionNewsService->create($request);

        Toastr::success('Thêm thành công');
        return redirect()->route('admin.promotion-news.index');
    }

    public function edit(Request $request, $id)
    {
        $news = $this->promotionNewsService
            ->getPermissionQuery($request)
            ->findOrFail($id);

        $list_code_0 = $this->promotionService->getListCode();

        return view('admin.promotion-news.edit', [
            'promotion' => $news,
            'list_code_0' => $list_code_0
        ]);
    }

    public function update(UpdateRequest $request, int $id)
    {
        $news = $this->promotionNewsService
            ->getPermissionQuery($request)
            ->findOrFail($id);

        $this->promotionNewsService->update($news, $request);

        Toastr::success('Cập nhật thành công');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $result = $this->promotionNewsService->action($request, AdminActionEnum::DELETE);

        $result
            ? Toastr::success('Chuyển vào thùng rác thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $result = $this->promotionNewsService->action($request, AdminActionEnum::RESTORE);

        $result
            ? Toastr::success('Khôi phục thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $result = $this->promotionNewsService->action($request, AdminActionEnum::FORCE_DELETE);

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
            $item = $this->promotionNewsService
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
