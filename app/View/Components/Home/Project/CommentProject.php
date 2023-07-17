<?php

namespace App\View\Components\Home\Project;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class CommentProject extends Component
{
    public $is_admin_view;
    public $user;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($is_admin_view = false)
    {
        $this->is_admin_view = $is_admin_view;
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
        return view('components.home.project.comment-project');
    }
    public function get_rating ($rateObject)
    {
        if ($rateObject) {
            if ($rateObject->one_star == 1)
                return 1;
            else if ($rateObject->two_star == 1)
                return 2;
            else if ($rateObject->three_star == 1)
                return 3;
            else if ($rateObject->four_star == 1)
                return 4;
            else if ($rateObject->five_star == 1)
                return 5;
            else return 0;
        }return 0;
    }
}
