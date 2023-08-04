<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateNewRequest;
use App\Http\Requests\Admin\UpdateNewRequest;
use App\Models\Admin;
use App\Models\News;
use App\Services\Admins\NewService;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewController extends Controller
{
    private NewService $newService;

    public function __construct()
    {
        $this->newService = new NewService;
    }

    public function index(Request $request)
    {
        $news = $this->newService->index($request->all());
        $statuses = [
            [
                'value' => '0',
                'label' => 'Ẩn',
            ],
            [
                'value' => '1',
                'label' => 'Hiện',
            ]
        ];
        $deleteStatuses = [
            [
                'value' => 'active',
                'label' => 'Chưa xóa',
            ],
            [
                'value' => 'with',
                'label' => 'Cùng với xóa',
            ],
            [
                'value' => 'only',
                'label' => 'Chỉ xóa',
            ],
        ];
        $authors = Admin::select('admin.id','admin.admin_fullname')
            ->join('news','admin.id','news.created_by')
            ->groupBy('admin.id')
            ->get();
        $groups = $this->newService->getValidGroups();
        $types = [
            [
                'value' => '0',
                'label' => 'Thường',
            ],
            [
                'value' => '1',
                'label' => 'Nổi bật',
            ],
            [
                'value' => '2',
                'label' => 'Quảng cáo',
            ],
        ];

        return view('admin.news.index', [
            'news' => $news,
            'statuses' => $statuses,
            'authors' => $authors,
            'groups' => $groups,
            'types' => $types,
            'deleteStatuses' => $deleteStatuses,
        ]);
    }

    public function create()
    {
        $params = $this->newService->getFormParams();
        $params['new'] = new News([]);

        return view('admin.news.create', $params);
    }

    public function store(CreateNewRequest $request)
    {
        $this->newService->create($request);

        Toastr::success('Tạo thành công.');
        return redirect(route('admin.news.index'));
    }

    public function edit(News $new)
    {
        $params = $this->newService->getFormParams();
        $params['news'] = $new;

        return view('admin.news.edit', $params);
    }

    public function update(News $new, UpdateNewRequest $request)
    {
        $this->newService->update($new, $request);

        Toastr::success('Cập nhật thành công.');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        News::query()
            ->find($ids)
            ->each(function($item) {
                $item->delete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        News::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->restore();
            });

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        News::withIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function highlight(News $new)
    {
        if ($new->isHighlight()) {
            Toastr::error("Đã là tin nổi bật");
            return back();
        }

        $new->update([
            'renew_at' => now(),
            'is_highlight' => 1,
            'highlight_start' => now()->startOfDay(),
            'highlight_end' => now()->endOfDay(),
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => strtotime(Carbon::now()),
        ]);

        Toastr::success("Nổi bật tiêu điểm thành công");
        return back();
    }

    // make news to ads
    public function express(News $new)
    {
        if($new->isAds()) {
            Toastr::error("Đã là tin quảng cáo");
            return back();
        }

        $new->update([
            'renew_at' => now(),
            'is_express' => 1,
            'express_start' => now()->startOfDay(),
            'express_end' => now()->endOfDay(),
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => strtotime(Carbon::now()),
        ]);

        Toastr::success("Quảng cáo tiêu điểm thành công");
        return back();
    }

    public function unExpress(News $new)
    {
        if(!$new->isAds()) {
            Toastr::error("Đây là tin thường không thể hủy quảng cáo");
            return back();
        }

        $new->update([
            'is_express' => 0,
            'express_start' => null,
            'express_end' => null,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => strtotime(Carbon::now()),
        ]);

        Toastr::success("Hủy quảng cáo tiêu điểm thành công");
        return back();
    }

    public function changeShow(News $new)
    {
        $new->update([
            'is_show' => !$new->is_show
        ]);

        Toastr::success("Đổi trạng thái thành công");
        return back();
    }
}
