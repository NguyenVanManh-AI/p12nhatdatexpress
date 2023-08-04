<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class TableFilter extends Component
{
    public $searchFilter;
    public $searchPlaceHolder;
    public $deleteFilter;
    public $dateRangeFilter;
    public $addRoute;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $searchFilter = true,
        $searchPlaceHolder = 'Từ khóa...',
        $deleteFilter = false,
        $dateRangeFilter = false,
        $addRoute = null
    ) {
        $this->searchFilter = $searchFilter;
        $this->searchPlaceHolder = $searchPlaceHolder;
        $this->deleteFilter = $deleteFilter;
        $this->dateRangeFilter = $dateRangeFilter;
        $this->addRoute = $addRoute;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.table-filter');
    }
}
