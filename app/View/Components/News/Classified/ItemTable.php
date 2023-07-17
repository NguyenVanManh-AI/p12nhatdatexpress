<?php

namespace App\View\Components\News\Classified;

use Illuminate\View\Component;

class ItemTable extends Component
{
    public $item;
    public $properties;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
        $this->properties = getCacheProperties();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news.classified.item-table');
    }
}
