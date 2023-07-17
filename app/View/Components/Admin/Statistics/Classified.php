<?php

namespace App\View\Components\Admin\Statistics;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Classified extends Component
{
    public $total_classified;
    public $province_classified;
    public $group_classified;

    public $classified_current;
    public $classified_sell_current;
    public $classified_rent_current;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $classified_controller = app('App\Http\Controllers\Admin\DataStatistics\ClassifiedStatisticController');

        $this->total_classified = $classified_controller->get_classified();

        $this->province_classified = $classified_controller->get_classified_by_province();

        $this->group_classified = $classified_controller->get_classified_by_group();

        $this->classified_current = $classified_controller->classified_month()->getData();

        $this->classified_sell_current = $classified_controller->classified_month(1, 2)->getData();

        $this->classified_rent_current = $classified_controller->classified_month(1, 10)->getData();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.statistics.classified');
    }
}
