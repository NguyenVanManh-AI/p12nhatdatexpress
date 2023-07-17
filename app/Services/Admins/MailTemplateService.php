<?php

namespace App\Services\Admins;

use App\Models\AdminMailTemplate;

class MailTemplateService
{
    /**
     * get mail template list
     * @param array $queries = []
     *
     * @return $templates
     */
    public function index(array $queries = [])
    {
        $itemsPerPage = data_get($queries, 'items') ?: 10;
        $page = data_get($queries, 'page') ?: 1;

        $listScope = data_get($queries, 'request_list_scope');
        $roleId = data_get($queries, 'admin_rol_id');
        $adminId = data_get($queries, 'admin_id');

        $filters = [
            'keyword' => data_get($queries, 'keyword'),
            'trashed' => data_get($queries, 'trashed'),
        ];

        $templates = AdminMailTemplate::select('admin_mail_template.*')
            ->when($listScope, function ($query, $listScope) use ($roleId, $adminId) {
                switch ($listScope) {
                    case 2:
                        return $query->join('admin', 'admin_mail_template.created_by', '=', 'admin.id')
                            ->where('admin.rol_id', $roleId);
                        break;
                    case 3:
                        return $query->where('admin_mail_template.created_by', $adminId);
                        break;
                    default:
                        return $query;
                        break;
                }
            })
            ->system()
            ->whereNotNull('admin_mail_template.template_action')
            ->filter($filters)
            ->orderBy('admin_mail_template.show_order', 'DESC')
            ->orderBy('admin_mail_template.id', 'DESC')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $templates;
    }

    /**
     * get mail template trash
     * @param array $queries = []
     *
     * @return $templates
     */
    public function getTrash(array $queries = [])
    {
        $itemsPerPage = data_get($queries, 'items') ?: 10;
        $page = data_get($queries, 'page') ?: 1;

        $listScope = data_get($queries, 'request_list_scope');
        $roleId = data_get($queries, 'admin_rol_id');
        $adminId = data_get($queries, 'admin_id');

        $templates = AdminMailTemplate::select('admin_mail_template.*')
            ->when($listScope, function ($query, $listScope) use ($roleId, $adminId) {
                switch ($listScope) {
                    case 2:
                        return $query->join('admin', 'admin_mail_template.created_by', '=', 'admin.id')
                            ->where('admin.rol_id', $roleId);
                        break;
                    case 3:
                        return $query->where('admin_mail_template.created_by', $adminId);
                        break;
                    default:
                        return $query;
                        break;
                }
            })
            ->system()
            ->whereNotNull('admin_mail_template.template_action')
            ->orderBy('admin_mail_template.show_order', 'DESC')
            ->orderBy('admin_mail_template.id', 'DESC')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $templates;
    }

    /**
     * get mail template query from permission
     * 
     * @return $query
     */
    public function getPermissionQuery()
    {
        // should check by permission
        $query = AdminMailTemplate::query();

        return $query;
    }
}
