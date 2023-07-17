<?php

namespace App\Http\Controllers\Admin\DataStatistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataStatisticsController extends Controller
{
    protected $ONE_DATE_TIME = 86399;

    public function dashboard(){
        return view('Admin.DataStatistics.DataStatistics');
    }

    //--------------------------------------------RANGE WITH SPECIFIC DATE--------------------------------------------//
    // Month range
    protected function get_range_month($start_of_day){
        return [strtotime('first day of this month', $start_of_day), strtotime('last day of this month', $start_of_day) + $this->ONE_DATE_TIME];
    }

    // Week range
    protected function get_range_week($start_of_day, $week){
        $first_of_week = $start_of_day +  ($this->ONE_DATE_TIME + 1) * 7 * $week;
        $last_of_week = $first_of_week + ($this->ONE_DATE_TIME * 7);

        return [ $first_of_week ,  $last_of_week];
    }

    // Year range
    protected function get_range_year($start_of_day){
        return [strtotime('first day of january this year', $start_of_day), strtotime('last day of december this year', $start_of_day) + $this->ONE_DATE_TIME];
    }

    //-------------------------------------------RANGE WITH CURRENT OF DATE-------------------------------------------//
    // Current date range
    protected function get_range_current_date($period = 0): array
    {
        // $period : -1  Previous Date
        // $period : 0  Current Date
        // $period : 1  Next Date
        switch ($period){
            case -1:
                $start_of_date = strtotime('previous day', mktime(0, 0, 0));
                $end_of_date = strtotime('previous day', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;

            case 1:
                $start_of_date = strtotime('next day', mktime(0, 0, 0));
                $end_of_date = strtotime('next day', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;

            default:
                $start_of_date = strtotime('today', mktime(0, 0, 0));
                $end_of_date = strtotime('today', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;
        }

        return [$start_of_date, $end_of_date];
    }

    // Current week range
    protected function get_range_current_week($period = 0): array
    {
        // $period : -1  Previous Week
        // $period : 0  Current Week
        // $period : 1  Next Week
        switch ($period){
            case -1:
                $start_of_week = strtotime('monday previous week', mktime(0, 0, 0));
                $end_of_week = strtotime('sunday previous week', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;

            case 1:
                $start_of_week = strtotime('monday next week', mktime(0, 0, 0));
                $end_of_week = strtotime('sunday next week', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;

            default:
                $start_of_week = strtotime('monday this week', mktime(0, 0, 0));
                $end_of_week = strtotime('sunday this week', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;
        }

        return [$start_of_week, $end_of_week];
    }

    // Current month range
    protected function get_range_current_month($period = 0): array
    {
        // $period : -1  Previous Month
        // $period : 0  Current Month
        // $period : 1  Next Month
        switch ($period){
            case -1:
                $start_of_month = strtotime('first day of previous month', mktime(0, 0, 0));
                $end_of_month = strtotime('last day of previous month', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;

            case 1:
                $start_of_month = strtotime('first day of next month', mktime(0, 0, 0));
                $end_of_month = strtotime('last day of next month', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;

            default:
                $start_of_month = strtotime('first day of this month', mktime(0, 0, 0));
                $end_of_month = strtotime('last day of this month', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;
        }

        return [$start_of_month, $end_of_month];
    }

    // Current month range
    protected function get_range_current_year($period = 0): array
    {
        // $period : -1  Previous Year
        // $period : 0  Current Year
        // $period : 1  Next Year
        switch ($period){
            case -1:
                $start_of_year = strtotime('first day of january previous year', mktime(0, 0, 0));
                $end_of_year = strtotime('last day of december previous year', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;

            case 1:
                $start_of_year = strtotime('first day of january next year', mktime(0, 0, 0));
                $end_of_year = strtotime('last day of december next year', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;

            default:
                $start_of_year = strtotime('first day of january this year', mktime(0, 0, 0));
                $end_of_year = strtotime('last day of december this year', mktime(0, 0, 0)) + $this->ONE_DATE_TIME;
                break;
        }

        return [$start_of_year, $end_of_year];
    }
}
