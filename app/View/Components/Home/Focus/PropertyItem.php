<?php

namespace App\View\Components\Home\Focus;

use Illuminate\View\Component;

class PropertyItem extends Component
{
    public $group;
    public $property;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $property,
        $group = null
    ) {
        $this->property = $property;
        $this->group = $group;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.property-item');
    }
}
