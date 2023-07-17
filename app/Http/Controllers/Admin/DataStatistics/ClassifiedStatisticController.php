<?php

namespace App\Http\Controllers\Admin\DataStatistics;

use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedLocation;
use App\Models\Group;
use App\Models\Province;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClassifiedStatisticController extends DataStatisticsController
{
    public const FILTER_BY_GROUP = 1;
    public const GROUP_PARENT_CLASSIFIED_ARRAY = [2, 10, 18, 19, 20];

    //-------------------------------------------CURRENT STATISTICS---------------------------------------------------//
    // CURRENT CLASSIFIED WEEk
    public function classified_week($is_group = false, $group_id = false): JsonResponse
    {
        $data = $this->get_classified($is_group, $group_id, $this->get_range_current_week());
        $data_previous = $this->get_classified($is_group, $group_id, $this->get_range_current_week(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tuần",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    // CURRENT CLASSIFIED MONTH
    public function classified_month($is_group = false, $group_id = false): JsonResponse
    {
        $data = $this->get_classified($is_group, $group_id, $this->get_range_current_month());
        $data_previous = $this->get_classified($is_group, $group_id, $this->get_range_current_month(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tháng",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    // CURRENT CLASSIFIED YEAR
    public function classified_year($is_group = false, $group_id = false): JsonResponse
    {
        $data = $this->get_classified($is_group, $group_id, $this->get_range_current_year());
        $data_previous = $this->get_classified($is_group, $group_id, $this->get_range_current_year(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Năm",
            'data' => number_format($data, 0, '.', '.'),
            'percent' => $percent,
        ]);
    }

    //-------------------------------------------TOTALLY STATISTICS---------------------------------------------------//
    // CLASSIFIED MONTH
    public function total_classified_month(): JsonResponse
    {
        for ($i = 1; $i<= 12; $i++)
        {
            $start_of_day = mktime(0, 0, 0, $i);
            $data[$i] = $this->get_classified(0, 0, $this->get_range_month($start_of_day));
        }
        return response()->json([
            'title' => "Tháng",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

    // CLASSIFIED WEEK
    public function total_classified_week(): JsonResponse
    {
        for ($i = 0; $i<= 3; $i++)
        {
            $start_of_day = strtotime('first monday of this month', mktime(0, 0, 0));
            $data[$i + 1] = $this->get_classified(0, 0, $this->get_range_week($start_of_day, $i));
        }
        return response()->json([
            'title' => "Tuần",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

    // CLASSIFIED YEAR
    public function total_classified_year(): JsonResponse
    {
        $year = (int) date('Y');
        $count_loop = 0;
        $previous_year = $year - 2; // 2 current years
        for ($i = $previous_year; $i<= $year ; $i++)
        {
            $count_loop++;
            $start_of_day = strtotime("01/01/$i");
            $data[$i] = $this->get_classified(0, 0, $this->get_range_year($start_of_day));
        }
        return response()->json([
            'title' => "Năm",
            'data' => array_values($data),
            'labels' => array_keys($data)
        ]);
    }

    // CLASSIFIED BY PROVINCE FOLLOW MONTH
    public function province_classified_month(): JsonResponse
    {
        $data = $this->get_classified(false, false, $this->get_range_current_month());
        $data_previous = $this->get_classified(false, false, $this->get_range_current_month(-1));
        $percent =  round(($data - $data_previous) / ($data_previous > 0 ? $data_previous : 1)  * 100);

        return response()->json([
            'title' => "Tháng",
            'data' => $data,
            'percent' => $percent,
        ]);
    }

    //-------------------------------------------SUPPORT METHOD-------------------------------------------------------//
    // Get classified
    public function get_classified($type = false, $identify = false, $filter_time_callback = false): int
    {
        $query = Classified::query();

        // Filter parameters
        if ($type == self::FILTER_BY_GROUP) {
            $children_list = Group::select('id')->where('parent_id', $identify)->get()->pluck('id')->toArray();
            $query = $query->whereIn('group_id', array_values($children_list));
        }

        // Filter time
        if ($filter_time_callback){
            $query = $query->whereBetween('created_at', $filter_time_callback);
        }

        return $query->count();
    }

    // Get classified with province
    public function get_classified_by_province(): Collection
    {
        $list_province = Province::query()
            ->leftJoin('classified_location', 'classified_location.province_id', '=', 'province.id')
            ->leftJoin('classified', 'classified_location.classified_id', '=', 'classified.id')
            ->select('province.id', 'province.province_name', DB::raw('COUNT(classified_id) as classified_count'))
            ->groupBy('province.id','province_name')
            ->orderBy('classified_count', 'desc')
            ->get();

        foreach ($list_province as $province){
            $previous_data = $this->get_num_classified_by_province($province->id, $this->get_range_current_month(-1));
            $province->percent =  round(($province->classified_count - $previous_data) / ($previous_data > 0 ? $previous_data : 1)  * 100);
        }

        return $list_province;
    }

    // Get classified with province
    public function get_classified_by_group(): Collection
    {

        $list_group = Group::query()
            ->leftJoin('classified', 'classified.group_id', '=', 'group.id')
            ->select('group.id', 'group.group_name', DB::raw('COUNT(classified.id) as classified_count'))
            ->groupBy('group.id', 'group.group_name')
            ->orderBy('classified_count', 'desc')
            ->whereIn('parent_id', self::GROUP_PARENT_CLASSIFIED_ARRAY)
            ->get();

        foreach ($list_group as $group){
            $previous_data = $this->get_num_classified_by_group($group->id, $this->get_range_current_month(-1));
            $group->percent =  round(($group->classified_count - $previous_data) / ($previous_data > 0 ? $previous_data : 1)  * 100);
        }

        return $list_group;
    }

    // Get classified by month with province
     public function get_num_classified_by_province($id, $filter_time_callback = false): int
     {
        $query = ClassifiedLocation::query()
            ->leftJoin('classified', 'classified_location.classified_id', '=', 'classified.id')
            ->where('province_id', $id);

        if ($filter_time_callback)
            $query = $query->whereBetween('classified.created_at', $filter_time_callback);

        return $query->count('classified_id');
     }

    // Get classified by month with group
    public function get_num_classified_by_group($id, $filter_time_callback = false): int
    {
        $query = Classified::where('group_id', $id);

        if ($filter_time_callback)
            $query = $query->whereBetween('classified.created_at', $filter_time_callback);

        return $query->count('id');
    }

}
