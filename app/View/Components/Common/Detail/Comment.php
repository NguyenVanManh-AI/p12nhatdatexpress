<?php

namespace App\View\Components\Common\Detail;

use Illuminate\View\Component;

class Comment extends Component
{
    public $comments;
    public $detailType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($comments, $detailType)
    {
        $this->comments = $comments;
        $this->detailType = $detailType;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.detail.comment');
    }
}
