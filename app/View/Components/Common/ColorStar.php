<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class ColorStar extends Component
{
    public $stars;
    public $type;
    public $actionInputName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($stars = 0, $type = null, $actionInputName = null)
    {
        $this->stars = $stars;
        $this->type = $type;
        $this->actionInputName = $actionInputName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.color-star');
    }
}
