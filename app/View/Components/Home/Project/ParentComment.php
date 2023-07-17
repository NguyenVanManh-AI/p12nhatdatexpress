<?php

namespace App\View\Components\Home\Project;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ParentComment extends Component
{
    public $comment;
    public $user;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
        if (Auth::guard('admin')->check())
            $this->user = Auth::guard('admin')->user();
        else if (Auth::check())
            $this->user = Auth::user();
        else $this->user = null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.project.parent-comment');
    }
}
