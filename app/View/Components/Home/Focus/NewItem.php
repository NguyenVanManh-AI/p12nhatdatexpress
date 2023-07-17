<?php

namespace App\View\Components\Home\Focus;

use Illuminate\View\Component;

class NewItem extends Component
{
    public $new;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($new)
    {
        $this->new = $new;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.new-item');
    }
}
