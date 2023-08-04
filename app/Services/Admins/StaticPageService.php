<?php

namespace App\Services\Admins;

use App\Enums\AdminActionEnum;
use App\Models\StaticPage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StaticPageService
{
    public $current_name = null;

    /**
     * get mail staticPage list
     * @param $request
     *
     * @return $staticPages
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

        $staticPages = $this->getPermissionQuery($permissionQueries, $trashed)
            ->filter($filters);

        if ($startDate) {
            $endDate
                ? $staticPages->whereBetween('static_page.created_at', [$startDate, $endDate])
                : $staticPages->where('static_page.created_at', $startDate);
        }

        $staticPages = $staticPages->latest('static_page.show_order')
            ->latest('static_page.id')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $staticPages;
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
            ->each(function($item) use ($action) {
                switch ($action) {
                    case AdminActionEnum::DUPLICATE:
                        $staticPage = $item->replicate();
                        $this->current_name = $staticPage->page_title;
                        $staticPage->page_title = $this->generateName($staticPage->page_title);
                        $staticPage->page_url = Str::slug($staticPage->page_title);
                        $staticPage->meta_title = $staticPage->page_title;
                        $staticPage->meta_key = $staticPage->page_title;
                        $this->create($staticPage);
                        break;
                    default:
                        adminCommonAction($item, $action);
                        break;
                }
            });

        return count($results) ? true : false;
    }

    /**
     * Create new static page
     *
     * @param $data
     * @return void
     */
    public function create($data)
    {
        StaticPage::create([
            'page_title' => $data->page_title,
            'image_url' => $data->image_url,
            'page_description' => $data->page_description,
            'page_content' => $data->page_content,
            'is_highlight' => $data->is_highlight,
            // 'page_group' => $data->page_group,
            'page_url' => $data->page_url,
            'meta_title' => $data->meta_title,
            'meta_key' => $data->meta_key,
            'show_order' => $data->show_order ?? 0,
            'meta_desc' => $data->meta_desc,
            'created_by' => Auth::guard('admin')->user()->id,
            'created_at' => time()
        ]);
    }

    public function generateName($name)
    {
        // old should check
        $num_exist = StaticPage::where('page_title', $name)->count('id');
        if ($num_exist == 0) return $name;

        if ($this->current_name != null && preg_match('/.+\(\d\)/', $this->current_name)) {
            $new_name = $this->current_name . "($num_exist)";
            $this->current_name = null;
            return $this->generateName($new_name);
        }

        if (preg_match('/.+\(\d\)/', $name)) {
            $stt = substr($name, -2, 1);
            is_numeric($stt) ? $num_exist = $stt + 1 : $num_exist++;
            $new_name = mb_substr($name, 0, -3);
            $new_name .= "($num_exist)";
        } else
            $new_name = $name . "($num_exist)";

        return $this->generateName($new_name);
    }

    /**
     * get mail staticPage query from permission
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

        $query = StaticPage::select('static_page.*')
            ->showed();

        $params = [
            'request_list_scope' => $listScope,
            'role_id' => $roleId,
            'admin_id' => $adminId,
            'trashed' => $trashed
        ];

        adminScopeQuery(
            $query,
            $params,
            StaticPage::class,
        );

        return $query;
    }
}
