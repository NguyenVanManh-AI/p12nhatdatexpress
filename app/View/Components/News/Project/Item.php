<?php

namespace App\View\Components\News\Project;

use Illuminate\View\Component;

class Item extends Component
{
    public $item;
    public $provinces;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
        $this->provinces = get_cache_province();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news.project.item');
    }
}
