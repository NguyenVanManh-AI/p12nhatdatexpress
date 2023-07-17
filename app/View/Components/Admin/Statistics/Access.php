<?php

namespace App\View\Components\Admin\Statistics;

use Illuminate\View\Component;

class Access extends Component
{
    public $access_total;
    public $access_today;
    public $access_month;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $access_controller = app('App\Http\Controllers\Admin\DataStatistics\AccessStatisticController');

        $this->access_total = $access_controller->get_access();
        $this->access_today = $access_controller->access_today();
        $this->access_month = $access_controller->access_month()->getData();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.statistics.access');
    }
}
