<?php

use App\Enums\AdminActionEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** get table joined
 *
 * @param $query
 * @param $table
 * @return bool
 */
function tableJoined($query, $table)
{
    $joins = collect($query->getQuery()->joins);

    return $joins->pluck('table')->contains($table);
}

/** get table joined
 *
 * @param $queries
 * @param $params
 * @param $model
 * @return bool
 */
function adminScopeQuery($queries, $params, $model)
{
    $listScope = data_get($params, 'request_list_scope');
    $roleId = data_get($params, 'role_id');
    $adminId = data_get($params, 'admin_id');
    $trashed = data_get($params, 'trashed');
    $table = app($model)->getTable();

    $queries = $queries  ->when($trashed, function ($query, $trashed) use ($model) {
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
                if ($trashed === 'with') {
                    $query->withTrashed();
                } else {
                    $query->onlyTrashed();
                }
            } else if (in_array('App\Traits\Models\SoftTrashed', class_uses($model))) {
                if ($trashed === 'with') {
                    $query->withIsDeleted();
                } else {
                    $query->onlyIsDeleted();
                }
            }

            return $query;
        })
        ->when($listScope, function ($query, $listScope) use ($roleId, $adminId, $table) {
            switch ($listScope) {
                case 2:
                    if (!tableJoined($query, 'admin')) {
                        $query = $query->join('admin', $table . '.created_by', '=', 'admin.id');
                    }

                    return $query->where('admin.rol_id', $roleId);
                    break;
                case 3:
                    return $query->where($table . '.created_by', $adminId);
                    break;
                default:
                    return $query;
                    break;
        }
    });

    return $queries;
}

/**
 * Get action params
 * 
 * @param $request
 * @param $action
 * @return array [$actionRequest, $ids, $trashed]
 */
function getActionsParams($request, $action): array
{
    $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

    $actionRequest = new Request([
        'request_list_scope' => $request->request_list_scope
    ]);

    $trashedMap = [
        AdminActionEnum::RESTORE => 'only',
        AdminActionEnum::FORCE_DELETE => 'with'
    ];

    $trashed = data_get($trashedMap, $action);
    return [$actionRequest, $ids, $trashed];
}

/**
 * Admin common action (delete, restore, force delete)
 * 
 * @param $item
 * @param $action
 * @return void
 */
function adminCommonAction($item, $action)
{
    switch ($action) {
        case AdminActionEnum::DELETE:
            $item->delete();
            break;
        case AdminActionEnum::RESTORE:
            $item->restore();
            break;
        case AdminActionEnum::FORCE_DELETE:
            $item->forceDelete();
            break;
        default:
            break;
    }
}

/**
 * Format timestamp
 * 
 * @param int $timestamp
 * @param string $format = 'd/m/Y H:i'
 * @return string $time
 */
function formatFromTimestamp($timestamp, $format = 'd/m/Y H:i')
{
    return Carbon::createFromTimestamp($timestamp)->format($format);
}