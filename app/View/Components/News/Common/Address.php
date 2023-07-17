<?php

namespace App\View\Components\News\Common;

use Illuminate\View\Component;

class Address extends Component
{
    public $provinces;
    public $showError;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($showError = false)
    {
        $this->provinces = get_cache_province();
        $this->showError = $showError;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news.common.address');
    }
}
