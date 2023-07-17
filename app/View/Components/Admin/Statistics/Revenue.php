<?php

namespace App\View\Components\Admin\Statistics;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use \App\Http\Controllers\Admin\DataStatistics\RevenueStatisticController;

class Revenue extends Component
{
    public $total_revenue;
    public $coin_revenue;
    public $other_revenue;
    public $coin_revenue_week;
    public $other_revenue_month;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $revenue_controller = app('App\Http\Controllers\Admin\DataStatistics\RevenueStatisticController');

        $this->total_revenue = $revenue_controller->get_revenue();
        $this->coin_revenue = $revenue_controller->get_revenue(1);
        $this->other_revenue = $revenue_controller->get_revenue(2);

        $this->coin_revenue_week = $revenue_controller->revenue_week(1)->getData();
        $this->other_revenue_month = $revenue_controller->revenue_month(2)->getData();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.statistics.revenue');
    }
}
