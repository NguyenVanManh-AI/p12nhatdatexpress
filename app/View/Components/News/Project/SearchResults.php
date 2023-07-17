<?php

namespace App\View\Components\News\Project;

use Illuminate\View\Component;

class SearchResults extends Component
{
    public $group;
    public $projects;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($group, $projects)
    {
        $this->group = $group;
        $this->projects = $projects;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news.project.search-results');
    }
}
