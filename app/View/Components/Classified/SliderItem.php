<?php

namespace App\View\Components\Classified;

use Illuminate\View\Component;

class SliderItem extends Component
{
    public $item;
    public $showViewTag;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $showViewTag = false)
    {
        $this->item = $item;
        $this->showViewTag = $showViewTag;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.classified.slider-item');
    }
}
