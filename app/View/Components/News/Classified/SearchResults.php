<?php

namespace App\View\Components\News\Classified;

use Illuminate\View\Component;

class SearchResults extends Component
{
    public $group;
    public $classifieds;
    public $province;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($group = null, $classifieds, $province = null)
    {
        $this->group = $group;
        $this->classifieds = $classifieds;
        $this->province = $province;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news.classified.search-results');
    }
}
