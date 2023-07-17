<?php

namespace App\Http\Controllers\Admin\DataStatistics;

use App\Models\User\Customer;
use Illuminate\Http\JsonResponse;

class CustomerStatisticController extends DataStatisticsController
{
    //-------------------------------------------CURRENT STATISTICS---------------------------------------------------//
    // CURRENT CUSTOMER WEEk
    public function current_customer_week(): JsonResponse
    {
        $data = $this->get_customer($this->get_range_current_week());
        $data_previous = $this->get_customer($this->get_range_current_week(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tuần",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    // CURRENT CUSTOMER MONTH
    public function current_customer_month(): JsonResponse
    {
        $data = $this->get_customer($this->get_range_current_month());
        $data_previous = $this->get_customer($this->get_range_current_month(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tháng",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    // CURRENT CUSTOMER YEAR
    public function current_customer_year(): JsonResponse
    {
        $data = $this->get_customer($this->get_range_current_year());
        $data_previous = $this->get_customer($this->get_range_current_year(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Năm",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    //-------------------------------------------TOTALLY STATISTICS---------------------------------------------------//
    // CUSTOMER MONTH
    public function customer_month(): JsonResponse
    {
        for ($i = 1; $i<= 12; $i++)
        {
            $start_of_day = mktime(0, 0, 0, $i);
            $data[$i] = $this->get_customer($this->get_range_month($start_of_day));
        }
        return response()->json([
            'title' => "Tháng",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

    // CUSTOMER WEEK
    public function customer_week(): JsonResponse
    {
        for ($i = 0; $i<= 3; $i++)
        {
            $start_of_day = strtotime('first monday of this month', mktime(0, 0, 0));
            $data[$i + 1] = $this->get_customer($this->get_range_week($start_of_day, $i));;
        }
        return response()->json([
            'title' => "Tuần",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

    // CUSTOMER YEAR
    public function customer_year(): JsonResponse
    {
        $year = (int) date('Y');
        $count_loop = 0;
        $previous_year = $year - 2; // 2 current years
        for ($i = $previous_year; $i<= $year ; $i++)
        {
            $count_loop++;
            $start_of_day = strtotime("01/01/$i");
            $data[$i] =$this->get_customer($this->get_range_year($start_of_day));;
        }
        return response()->json([
            'title' => "Năm",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

    //-------------------------------------------SUPPORT METHOD-------------------------------------------------------//
    // Get customer
    public function get_customer($filter_time_callback = null){
        $query = Customer::query();

        // Filter time
        if ($filter_time_callback){
            $query = $query->whereBetween('created_at', $filter_time_callback);
        }

        return $query->count('id');
    }

    // Get customer by group
    public function get_customer_by_group($parent_group ,$filter_time_callback = null){
        $query = Customer::query()
            ->leftJoin('classified', 'classified.id', '=', 'classified_id')
            ->leftJoin('group', 'group.id', '=', 'group_id')
            ->where('parent_id', $parent_group);

        // Filter time
        if ($filter_time_callback){
            $query = $query->whereBetween('created_at', $filter_time_callback);
        }

        return $query->count('customer.id');
    }

}
