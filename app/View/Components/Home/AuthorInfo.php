<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class AuthorInfo extends Component
{
    public $author;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($author)
    {
        $this->author = $author;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.author-info');
    }
}
