<?php

namespace App\Http\Controllers\Admin\DataStatistics;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AccessStatisticController extends DataStatisticsController
{
    public const TABLE = 'access_statistic';
    //-------------------------------------------CURRENT STATISTICS---------------------------------------------------//
    // CURRENT ACCESS WEEk
    public function access_today(): int
    {
        return $this->get_access($this->get_range_current_date());
    }

    // CURRENT ACCESS WEEk
    public function access_week(): JsonResponse
    {
        $data = $this->get_access($this->get_range_current_week());
        $data_previous = $this->get_access($this->get_range_current_week(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tuần",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    // CURRENT ACCESS MONTH
    public function access_month(): JsonResponse
    {
        $data = $this->get_access($this->get_range_current_month());
        $data_previous = $this->get_access($this->get_range_current_month(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tháng",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    // CURRENT ACCESS YEAR
    public function access_year(): JsonResponse
    {
        $data = $this->get_access($this->get_range_current_year());
        $data_previous = $this->get_access($this->get_range_current_year(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Năm",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }


    //-------------------------------------------SUPPORT METHOD-------------------------------------------------------//
    // Get classified
    public function get_access($filter_time_callback = false): int
    {
        $query = DB::table(self::TABLE);

        // Filter time
        if ($filter_time_callback){
            $query = $query->whereBetween('access_at', $filter_time_callback);
        }

        return $query->count('*');
    }

}
