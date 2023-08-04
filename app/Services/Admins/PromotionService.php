<?php

namespace App\Services\Admins;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PromotionService
{
    /**
     * get mail promotion list
     * @param $request
     *
     * @return $promotions
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

        $promotions = $this->getPermissionQuery($permissionQueries, $trashed)
            ->filter($filters);

        if ($startDate) {
            $endDate
                ? $promotions->whereBetween('promotion.created_at', [$startDate, $endDate])
                : $promotions->where('promotion.created_at', $startDate);
        }

        $promotions = $promotions->latest('promotion.id')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $promotions;
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
     * Create new promotion page
     *
     * @param $data
     * @return Promotion $promotion
     */
    public function create($data)
    {
    }

    /**
     * Create new promotion page
     *
     * @param Promotion $promotion
     * @param $request
     * @return Promotion $promotion
     */
    public function update(Promotion $promotion, $request)
    {
        $date_from = $request->date_from ?: now();
        $date_to = $request->date_to ?: now()->addYears(10);

        $data = [
            'promotion_type' => $request->promotion_type,
            'value' => $request->value,
            'num_use' => $request->num_use,
            'date_from' => Carbon::parse($date_from)->timestamp,
            'date_to' => Carbon::parse($date_to)->timestamp,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->user()->id
        ];

        if ($request->is_all == 'on') {
            $data['is_all'] = 1;
            $data['user_id_use'] = 0;
            $data['is_private'] = 0;
        }

        $promotion->update($data);

        return $promotion;
    }

    /**
     * get mail promotion query from permission
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

        $query = Promotion::select('promotion.*');

        $params = [
            'request_list_scope' => $listScope,
            'role_id' => $roleId,
            'admin_id' => $adminId,
            'trashed' => $trashed
        ];

        adminScopeQuery(
            $query,
            $params,
            Promotion::class,
        );

        return $query;
    }

    /**
     * Get list code
     * 
     * @return $listCode
     */
    public function getListCode()
    {
        return Promotion::query()
            ->where('is_private', 1)
            ->where('date_from', '<', time())
            ->where('date_to', '>', time())
            ->latest('id')
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'label' => $item->getSelectLabel()
                ];
            });
    }
}
