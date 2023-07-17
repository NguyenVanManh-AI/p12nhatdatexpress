<?php

namespace App\View\Components\Home\Project;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ChildrenComment extends Component
{
    public $comment;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.project.children-comment');
    }
}
