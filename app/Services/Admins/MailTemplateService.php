<?php

namespace App\Services\Admins;

use App\Models\AdminMailTemplate;
use Illuminate\Support\Facades\Auth;

class MailTemplateService
{
    /**
     * get mail template list
     * @param $request
     *
     * @return $templates
     */
    public function index($request, $trashed = null)
    {
        $itemsPerPage = $request->items ?: 10;
        $page = $request->page ?: 1;

        $filters = [
            'keyword' => $request->keyword,
        ];

        $permissionQueries = $request;

        $templates = $this->getPermissionQuery($permissionQueries, $trashed)
            ->filter($filters)
            ->latest('admin_mail_template.show_order')
            ->latest('admin_mail_template.id')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $templates;
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
                adminCommonAction($item, $action);
            });

        return count($results) ? true : false;
    }

    /**
     * get mail template query from permission
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

        $query = AdminMailTemplate::select('admin_mail_template.*')
            ->system()
            ->whereNotNull('admin_mail_template.template_action');

        $params = [
            'request_list_scope' => $listScope,
            'role_id' => $roleId,
            'admin_id' => $adminId,
            'trashed' => $trashed
        ];

        adminScopeQuery(
            $query,
            $params,
            AdminMailTemplate::class,
        );

        return $query;
    }
}
