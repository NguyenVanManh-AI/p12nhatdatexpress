<?php

namespace App\Services\Admins;

use App\CPU\HelperImage;
use App\Models\PromotionNew;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PromotionNewsService
{
    public $current_name = null;

    /**
     * get mail news list
     * @param $request
     *
     * @return $news
     */
    public function index($request, $trashed = null)
    {
        $itemsPerPage = $request->items ?: 10;
        $page = $request->page ?: 1;

        $filters = [
            'keyword' => $request->keyword,
        ];

        $startDate = $request->start_day ? Carbon::parse($request->start_day)->startOfDay()->timestamp : null;
        $endDate = $request->end_day ? Carbon::parse($request->end_day)->endOfDay()->timestamp : null;
        if ($startDate && $endDate && $startDate > $endDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $permissionQueries = $request;

        $news = $this->getPermissionQuery($permissionQueries, $trashed)
            ->filter($filters);

        if ($startDate) {
            $endDate
                ? $news->whereBetween('promotion_news.created_at', [$startDate, $endDate])
                : $news->where('promotion_news.created_at', $startDate);
        }

        $news = $news->latest('promotion_news.id')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $news;
    }

    /**
     * Common actions
     *
     * @param $request
     * @param $action
     * @return bool
     */
    public function action($request, $action): bool
    {
        [$actionRequest, $ids, $trashed] = getActionsParams($request, $action);

        $results = $this->getPermissionQuery($actionRequest, $trashed)
            ->find($ids)
            ->each(function ($item) use ($action) {
                adminCommonAction($item, $action);
            });

        return count($results) ? true : false;
    }

    /**
     * Create new promotion news
     *
     * @param $request
     * @return void
     */
    public function create($request)
    {
        $image = null;
        if ($request->hasFile('image')) {
            $image = HelperImage::saveImage('system/images/post_promotion', $request->file('image'));
        }

        PromotionNew::create([
            'promotion_id' => $request->promotion_id,
            'news_content' => $request->news_content,
            'news_description' => $request->news_description,
            'news_title' => $request->news_title,
            'image' => $image,

            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);
    }

    /**
     * Update data
     *
     * @param PromotionNew $news
     * @param $request
     * @return PromotionNew $news
     */
    public function update(PromotionNew $news, $request)
    {
        $data = [
            'promotion_id' => $request->promotion_id,
            'news_content' => $request->news_content,
            'news_description' => $request->news_description,
            'news_title' => $request->news_title,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => time(),
        ];

        if ($request->hasFile('image')) {
            $image = HelperImage::saveImage('system/images/post_promotion', $request->file('image'));
            $data['image'] = $image;
        }

        $news->update($data);
    }

    /**
     * get mail news query from permission
     *
     * @param $request
     * @param $trashed = null
     * @return $query
     */
    public function getPermissionQuery($request, $trashed = null)
    {
        if (!Auth::guard('admin')->user()) return;
        $listScope = $request->request_list_scope;

        $roleId = Auth::guard('admin')->user()->rol_id;
        $adminId = Auth::guard('admin')->user()->id;

        $query = PromotionNew::select('promotion_news.*');

        $params = [
            'request_list_scope' => $listScope,
            'role_id' => $roleId,
            'admin_id' => $adminId,
            'trashed' => $trashed
        ];

        adminScopeQuery(
            $query,
            $params,
            PromotionNew::class,
        );

        return $query;
    }
}
