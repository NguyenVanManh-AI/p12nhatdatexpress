<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class Map extends Component
{
    public $id;
    public $latName;
    public $latValue;
    public $longName;
    public $longValue;
    public $hint;
    public $hideHint;
    public $markerIcon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
     public function __construct(
        $latName = null,
        $latValue = null,
        $longName = null,
        $longValue = null,
        $id = null,
        $hint = '',
        $hideHint = false,
        $markerIcon = null
    ) {
        $this->latName = $latName;
        $this->latValue = $latValue;
        $this->id = $id;
        $this->longName = $longName;
        $this->longValue = $longValue;
        $this->hint = $hint;
        $this->hideHint = $hideHint;
        $this->markerIcon = $markerIcon ?: asset('/images/icons/red-marker.png');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.map');
    }
}
