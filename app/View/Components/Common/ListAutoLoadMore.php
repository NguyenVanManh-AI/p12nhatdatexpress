<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class ListAutoLoadMore extends Component
{
    public $itemClass;
    public $itemsPerRow;
    public $itemsPerPage;
    public $moreUrl;
    public $lists;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $itemClass,
        $itemsPerRow = 1,
        $itemsPerPage = 9,
        $moreUrl,
        $lists
    ) {
        $this->itemClass = $itemClass;
        $this->itemsPerRow = $itemsPerRow;
        $this->itemsPerPage = $itemsPerPage;
        $this->moreUrl = $moreUrl;
        $this->lists = $lists;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.list-auto-load-more');
    }
}
