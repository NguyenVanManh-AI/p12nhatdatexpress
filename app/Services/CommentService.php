<?php

namespace App\Services;

use App\Models\Classified\ClassifiedComment;
use App\Models\Group;

class CommentService
{
    /**
     * Get all classified comments
     * @param array $queries
     * 
     * @return Collection|null $classifiedComments
     */
    public function listClassifiedComments(array $queries = [])
    {
        $itemsPerPage = data_get($queries, 'items') ?: 10;
        $page = data_get($queries, 'page') ?: 1;
        $status = data_get($queries, 'status') ?: 3;
        $listScope = data_get($queries, 'request_list_scope');
        $adminRoleId = data_get($queries, 'admin_role_id');
        $adminId = data_get($queries, 'admin_id');
        $status = data_get($queries, 'status');

        $classifiedComments = ClassifiedComment::with('user', 'classified.group')
            ->select('classified_comment.id','classified_comment.comment_content','classified_comment.created_at','user.username','group.group_name','classified_comment.user_id as created_by','classified.classified_url','user.is_forbidden','user.is_locked',
                'user.email', 'user_detail.fullname', 'user.phone_number', 'classified_comment.classified_id'
                // 'group_parent.group_url as group_parent_url',
                // 'group_parent.group_name as group_parent_name',
                // 'group_parent_parent.group_url as group_parent_parent_url',
                // 'group_parent_parent.group_name as group_parent_parent_name'
            )
            ->join('user','classified_comment.user_id','user.id')
            ->join('user_detail','user_detail.user_id','user.id')
            ->join('classified','classified.id','classified_comment.classified_id')
            ->leftJoin('group','classified.group_id','group.id')
            // ->leftJoin('group as group_parent','group.parent_id','group_parent.id')
            // ->leftJoin('group as group_parent_parent','group_parent.parent_id','group_parent_parent.id')
            ->where('classified_comment.is_deleted',0)
            ->orderBy('classified_comment.id','desc')
            ->when($listScope, function ($query, $listScope) use ($adminRoleId, $adminId) {
                switch ($listScope) {
                    case 2:
                        $query->join('admin', 'classified.created_by', '=', 'admin.id')
                            ->where('admin.rol_id', $adminRoleId);
                        break;
                    case 3:
                        $query->where('classified.created_by', $adminId);
                        break;
                    default:
                        break;
                }
            })
            ->when(data_get($queries, 'from_date'), function ($query, $fromDate) {
                return $query->where('classified_comment.created_at', '>', date(strtotime($fromDate)));
            })
            ->when(data_get($queries, 'to_date'), function ($query, $toDate) {
                return $query->where('classified_comment.created_at', '<', date(strtotime($toDate)));
            })
            ->when(data_get($queries, 'category'), function ($query, $category) {
                return $query->where('group.id', $category);
            })
            ->when($status != null , function ($query) use ($status) {
                switch ($status) {
                    case 0:
                        $query->where([
                            'user.is_forbidden' => 0,
                            'user.is_locked' => 0,
                        ]);
                        break;
                    case 1:
                        $query->where('user.is_locked', 1);
                        break;
                    case 2:
                        $query->where('user.is_forbidden', 1);
                        break;
                    default:
                        break;
                }

                return $query;
            })
            ->when(data_get($queries, 'keyword'), function ($query, $keyWord) {
                return $query->where('classified_comment.comment_content', 'LIKE', '%' . $keyWord . '%')
                    ->where('user_detail.fullname', 'LIKE', '%' . $keyWord . '%')
                    ->orWhere('user.email', 'LIKE', '%' . $keyWord . '%')
                    ->orWhere('user.phone_number', 'LIKE', '%' . $keyWord . '%');
            });

        $classifiedComments = $classifiedComments->latest()
            ->distinct()
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $classifiedComments;
    }

    public function sortState(&$query, $queries)
    {
        # Lọc với tình trạng (state) => [
        # 0 => Chờ xác thực
        # 1 => Hoạt động
        # 2 => Đã chặn
        # 3 => Đã cấm
        # 4 => Đã xóa
        #  ]
        switch (data_get($queries, 'state')) {
            case 0:
                $query = $query
                    ->where('is_confirmed', 0)
                    ->where('user.is_deleted', 0)
                    ->where('is_forbidden', 0)
                    ->where('is_locked', 0);;
                break;
            case 1:
                $query = $query
                    ->where('is_confirmed', 1)
                    ->where('user.is_deleted', 0)
                    ->where('is_forbidden', 0)
                    ->where('is_locked', 0);
                break;
            case 2:
                $query = $query->where('is_locked', 1)->where('lock_time', '>', time());
                break;
            case 3:
                $query = $query->where('is_forbidden', 1);
                break;
            case 4:
                $query = $query->where('user.is_deleted', 1);
                break;
            default:
                break;
        }
    }
}

