<?php

namespace App\View\Components\User;

use Illuminate\View\Component;

class Guide extends Component
{
    public $guide;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($guide)
    {
        $this->guide = $guide;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.guide');
    }
}
