<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnterpriseService
{
    /**
     * get all enterprises
     * @param array $queries = []
     *
     * @return $enterprises
     */
    public function index(array $queries = [])
    {
        // $paginate = DB::table('admin_config')->where('config_code', 'C001')->pluck('config_value')->first();
        // $itemsPerPage = 20;
        // $itemsPerPage = (int) data_get($queries, 'items') ?: 20;
        $itemsPerPage = (int) data_get($queries, 'items') ?: 20;
        $page = (int) data_get($queries, 'page') ?: 1;

        $enterprises = User::select(
            'user.*',
            // DB::raw('count(user_rating.user_id) as count')
        )
        ->with('detail', 'location.province')
        ->withCount('ratingUsers')
        ->where('user.user_type_id', data_get($queries, 'user_type_id'))
        ->active()
        ->when(data_get($queries, 'province_id'), function ($query, $provinceId) {
            if (!tableJoined($query, 'user_location')) {
                $query = $query->leftJoin('user_location','user_location.user_id','user.id');
            }

            return $query->where('user_location.province_id', $provinceId);
        })
        ->when(data_get($queries, 'district_id'), function ($query, $districtId) {
            if (!tableJoined($query, 'user_location')) {
                $query = $query->leftJoin('user_location','user_location.user_id','user.id');
            }

            return $query->where('user_location.district_id', $districtId);
        })
        ->when(data_get($queries, 'rate'), function ($query, $rate) {
            return $query->where('user.rating', $rate);
        })
        ->when(data_get($queries, 'keyword'), function ($query, $keyword) {
            return $query->leftJoin('user_detail', 'user_detail.user_id', 'user.id')
                ->where('user_detail.fullname', 'LIKE', '%' . $keyword . '%');
        })
        ->when(data_get($queries, 'project'), function ($query, $project) {
            return $query->leftJoin('project_user', 'project_user.user_id', '=', 'user.id')
                ->leftJoin('project', 'project.id', '=', 'project_user.project_id')
                ->where('project.project_name', 'LIKE', '%' . $project . '%');
        })
        ->groupBy('user.id')
        ->latest('user.is_highlight')
        ->latest('user.highlight_time')
        ->latest('user.rating')
        ->latest('rating_users_count')
        ->skip(($page - 1) * $itemsPerPage)
        ->paginate($itemsPerPage);

        return $enterprises;
    }
}
