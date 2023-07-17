<?php

namespace App\Http\Controllers\Admin\DataStatistics;

use App\Models\User\UserDeposit;
use Illuminate\Http\JsonResponse;

class RevenueStatisticController extends DataStatisticsController
{
    protected $MILLION = 1000000;

    //-------------------------------------------CURRENT STATISTICS---------------------------------------------------//
    // CURRENT REVENUE COIN WEEk
    public function revenue_week($is_coin = 0): JsonResponse
    {
        $data = $this->get_revenue($is_coin, $this->get_range_current_week());
        $data_previous = $this->get_revenue($is_coin, $this->get_range_current_week(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tuần",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    // CURRENT REVENUE COIN MONTH
    public function revenue_month($is_coin = 0): JsonResponse
    {
        $data = $this->get_revenue($is_coin, $this->get_range_current_month());
        $data_previous = $this->get_revenue($is_coin, $this->get_range_current_month(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tháng",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    // CURRENT REVENUE COIN YEAR
    public function revenue_year($is_coin = 0): JsonResponse
    {
        $data = $this->get_revenue($is_coin, $this->get_range_current_year());
        $data_previous = $this->get_revenue($is_coin, $this->get_range_current_year(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Năm",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    //-------------------------------------------TOTALLY STATISTICS---------------------------------------------------//
    // REVENUE MONTH
    public function total_revenue_month(): JsonResponse
    {
        for ($i = 1; $i<= 12; $i++)
        {
            $start_of_day = mktime(0, 0, 0, $i);
            $data_coin[$i] = round($this->get_revenue(1, $this->get_range_month($start_of_day)) / $this->MILLION);
            $data_other[$i] = round($this->get_revenue(2, $this->get_range_month($start_of_day)) / $this->MILLION);
        }
        return response()->json([
            'title' => "Tháng",
            'data_coin' => array_values($data_coin),
            'data_other' => array_values($data_other),
            'labels' => array_keys($data_coin)
        ]);
    }

    // REVENUE WEEK
    public function total_revenue_week(): JsonResponse
    {
        for ($i = 0; $i<= 3; $i++)
        {
            $start_of_day = strtotime('first monday of this month', mktime(0, 0, 0));
            $data_coin[$i + 1] = round($this->get_revenue(1, $this->get_range_week($start_of_day, $i)) / $this->MILLION);
            $data_other[$i + 1] = round($this->get_revenue(2, $this->get_range_week($start_of_day, $i)) / $this->MILLION);
        }
        return response()->json([
            'title' => "Tuần",
            'data_coin' => array_values($data_coin),
            'data_other' => array_values($data_other),
            'labels' => array_keys($data_coin)
        ]);
    }

    // REVENUE YEAR
    public function total_revenue_year(): JsonResponse
    {
        $year = (int) date('Y');
        $count_loop = 0;
        $previous_year = $year - 2; // 2 current years
        for ($i = $previous_year; $i<= $year ; $i++)
        {
            $count_loop++;
            $start_of_day = strtotime("01/01/$i");
            $data_coin[$i] = round($this->get_revenue(1, $this->get_range_year($start_of_day)) / $this->MILLION);
            $data_other[$i] = round($this->get_revenue(2, $this->get_range_year($start_of_day)) / $this->MILLION);
        }
        return response()->json([
            'title' => "Năm",
            'data_coin' => array_values($data_coin),
            'data_other' => array_values($data_other),
            'labels' => array_keys($data_coin)
        ]);
    }

    //-------------------------------------------SUPPORT METHOD-------------------------------------------------------//
    // Get revenue
    public function get_revenue($is_coin = false, $filter_time_callback = null){
        // is_coin : 0  (Don't Filter coin)
        // is_coin : 1  (Filter coin)
        // is_coin : 2  (Filter <> coin)
        $query = UserDeposit::where(['is_confirm' => 1, 'deposit_status' => 1]);

        // Filter is coin
        if ($is_coin == 1){
            $query = $query->where('deposit_type', 'C');
        }elseif($is_coin == 2){
            $query = $query->where('deposit_type', '<>', 'C');
        }

        // Filter time
        if ($filter_time_callback){
            $query = $query->whereBetween('confirm_time', $filter_time_callback);
        }

        return $query->sum('deposit_amount');
    }

}
