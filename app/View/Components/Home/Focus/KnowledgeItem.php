<?php

namespace App\View\Components\Home\Focus;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class KnowledgeItem extends Component
{
    public $item;
    public $group;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $group)
    {
        $this->item = $item;
        $this->group = $group;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.knowledge-item');
    }
}
