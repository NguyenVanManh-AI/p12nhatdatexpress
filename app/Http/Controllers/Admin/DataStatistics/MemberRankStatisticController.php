<?php

namespace App\Http\Controllers\Admin\DataStatistics;

use App\Models\Classified\Classified;
use App\Models\User\UserDeposit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;

class MemberRankStatisticController extends DataStatisticsController
{
    public const LIMIT_TOP = 100;
    public const KTOCOIN = 10; // 1000 = 10 coin => 100.000 = 1000 coin

    //-------------------------------------------CURRENT STATISTICS---------------------------------------------------//
    // CURRENT CHARGE WEEk
    public function current_charge_week(): JsonResponse
    {
        $data = $this->get_users_top_charge($this->get_range_current_week());
        return response()->json([
            'title' => "Tuần",
            'data' => $data,
        ]);
    }

    // CURRENT CUSTOMER MONTH
    public function current_charge_month(): JsonResponse
    {
        $data = $this->get_users_top_charge($this->get_range_current_month());
        return response()->json([
            'title' => "Tháng",
            'data' => $data,
        ]);
    }

    // CURRENT CUSTOMER YEAR
    public function current_charge_year(): JsonResponse
    {
        $data = $this->get_users_top_charge($this->get_range_current_year());
        return response()->json([
            'title' => "Năm",
            'data' => $data,
        ]);
    }

    // CURRENT CLASSIFIED WEEk
    public function current_classified_week(): JsonResponse
    {
        $data = $this->get_users_top_classified($this->get_range_current_week());
        return response()->json([
            'title' => "Tuần",
            'data' => $data,
        ]);
    }

    // CURRENT CUSTOMER MONTH
    public function current_classified_month(): JsonResponse
    {
        $data = $this->get_users_top_classified($this->get_range_current_month());
        return response()->json([
            'title' => "Tháng",
            'data' => $data,
        ]);
    }

    // CURRENT CUSTOMER YEAR
    public function current_classified_year(): JsonResponse
    {
        $data = $this->get_users_top_classified($this->get_range_current_year());
        return response()->json([
            'title' => "Năm",
            'data' => $data,
        ]);
    }

    //-------------------------------------------TOTALLY STATISTICS---------------------------------------------------//
    // CHARGE
    public function charge_total() : JsonResponse{
        $data = $this->get_users_top_charge();
        return response()->json([
            'title' => "Tất cả",
            'data' => $data,
        ]);
    }

    // POST
    public function classified_total() : JsonResponse{
        $data = $this->get_users_top_classified();
        return response()->json([
            'title' => "Tất cả",
            'data' => $data,
        ]);
    }

    //-------------------------------------------SUPPORT METHOD-------------------------------------------------------//
    // Get user top charge
    public function get_users_top_charge($filter_time_callback = null): \Illuminate\Support\Collection
    {
        $ktocoin = self::KTOCOIN;
        $query = UserDeposit::query()
            ->select('user_deposit.user_id',
                DB::raw("count(user_deposit.user_id) as number_charge"),
                DB::raw(" $ktocoin * (sum(deposit_amount) / 1000) as total_charge"),
                'fullname', 'image_url', 'user.phone_number')
            ->join('user_detail', 'user_detail.user_id', '=', 'user_deposit.user_id')
            ->join('user', 'user.id', '=', 'user_deposit.user_id')
            ->where(['is_confirm' => 1, 'deposit_status' => 1, 'deposit_type' => 'C'])
            ->groupBy('user_deposit.user_id', 'fullname', 'image_url', 'user.phone_number')
            ->orderBy("total_charge", "desc")
            ->limit(self::LIMIT_TOP);

        if ($filter_time_callback)
            $query = $query->whereBetween('confirm_time', $filter_time_callback);

        return $query->get();
    }

    // Get user top post
    public function get_users_top_classified($filter_time_callback = null): \Illuminate\Support\Collection
    {
        $query = Classified::query()
            ->select('classified.user_id',
                DB::raw("count(classified.user_id) as total_post"), 'user_detail.fullname', 'user_detail.image_url', 'user.phone_number')
            ->join('user_detail', 'user_detail.user_id', '=', 'classified.user_id')
            ->join('user', 'user.id', '=', 'classified.user_id')
            ->groupBy('classified.user_id', 'user_detail.fullname', 'user_detail.image_url', 'user.phone_number')
            ->orderBy("total_post", "desc")
            ->limit(self::LIMIT_TOP);

        if ($filter_time_callback)
            $query = $query->whereBetween('classified.created_at', $filter_time_callback);

        return $query->get();
    }
}
