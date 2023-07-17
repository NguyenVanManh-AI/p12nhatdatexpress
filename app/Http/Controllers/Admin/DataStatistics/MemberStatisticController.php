<?php

namespace App\Http\Controllers\Admin\DataStatistics;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class MemberStatisticController extends DataStatisticsController
{
    // MEMBER MONTH
    public function member_month(): JsonResponse
    {
        for ($i = 1; $i<= 12; $i++)
        {
            $start_of_day = mktime(0, 0, 0, $i);
            $data[$i] = User::whereBetween('created_at', $this->get_range_month($start_of_day))->count();
        }
        return response()->json([
            'title' => "Tháng",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

    // MEMBER WEEK
    public function member_week(): JsonResponse
    {
        for ($i = 0; $i<= 3; $i++)
        {
            $start_of_day = strtotime('first monday of this month', mktime(0, 0, 0));
            $data[$i + 1] = User::whereBetween('created_at', $this->get_range_week($start_of_day, $i))->count();
        }
        return response()->json([
            'title' => "Tuần",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

    // MEMBER YEAR
    public function member_year(): JsonResponse
    {
        $year = (int) date('Y');
        $count_loop = 0;
        $previous_year = $year - 2; // 2 current years
        for ($i = $previous_year; $i<= $year ; $i++)
        {
            $count_loop++;
            $start_of_day = strtotime("01/01/$i");
            $data[$i] = User::whereBetween('created_at', $this->get_range_year($start_of_day))->count();
        }
        return response()->json([
            'title' => "Năm",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

}
