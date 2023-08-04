<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateFeaturedKeywordRequest;
use App\Http\Requests\Admin\UpdateFeaturedKeywordRequest;
use App\Models\District;
use App\Models\FeaturedKeyword;
use App\Models\Group;
use App\Services\FeaturedKeyWordService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class KeywordUseController extends Controller
{
    private FeaturedKeyWordService $keywordService;

    public function __construct()
    {
        $this->keywordService = new FeaturedKeyWordService;
    }

    public function index(Request $request)
    {
        $featuredKeywords = $this->keywordService->index($request->all());
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
        $types = [
            [
                'value' => Group::class,
                'label' => 'Mô hình',
            ],
            [
                'value' => District::class,
                'label' => 'Quận/Huyện',
            ]
        ];

        return view('admin.keyword-uses.index', [
            'featuredKeywords' => $featuredKeywords,
            'statuses' => $statuses,
            'deleteStatuses' => $deleteStatuses,
            'types' => $types
        ]);
    }

    public function create()
    {
        $params = $this->keywordService->getFormParams();
        $params['keyword'] = new FeaturedKeyword([
            'views' => 1,
            'target_type' => Group::class,
            'is_active' => 1
        ]);

        return view('admin.keyword-uses.create', $params);
    }

    public function store(CreateFeaturedKeywordRequest $request)
    {
        $this->keywordService->create($request->all());

        Toastr::success('Tạo thành công.');
        return redirect(route('admin.keywords.index'));
    }

    public function edit(FeaturedKeyword $keyword)
    {
        $params = $this->keywordService->getFormParams();
        $params['keyword'] = $keyword;

        return view('admin.keyword-uses.edit', $params);
    }

    public function update(FeaturedKeyword $keyword, UpdateFeaturedKeywordRequest $request)
    {
        $this->keywordService->update($keyword, $request->all());

        Toastr::success('Cập nhật thành công.');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        FeaturedKeyword::query()
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

        FeaturedKeyword::withTrashed()
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

        FeaturedKeyword::withTrashed()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }
}
