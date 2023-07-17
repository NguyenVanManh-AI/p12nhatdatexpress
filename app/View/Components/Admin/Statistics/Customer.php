<?php

namespace App\View\Components\Admin\Statistics;

use Illuminate\View\Component;

class Customer extends Component
{
    public $total_customer;
    public $week_customer;
    public $customer_sell;
    public $customer_rent;

    public const GROUP_SELL_ID = 2;
    public const GROUP_RENT_ID = 10;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $customer_controller = app('App\Http\Controllers\Admin\DataStatistics\CustomerStatisticController');

        $this->total_customer = $customer_controller->get_customer();
        $this->week_customer = $customer_controller->current_customer_week()->getData();
        $this->customer_sell = $customer_controller->get_customer_by_group(self::GROUP_SELL_ID);
        $this->customer_rent = $customer_controller->get_customer_by_group(self::GROUP_RENT_ID);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.statistics.customer');
    }
}
