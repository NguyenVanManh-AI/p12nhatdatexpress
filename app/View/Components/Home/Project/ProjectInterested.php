<?php

namespace App\View\Components\Home\Project;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ProjectInterested extends Component
{
    public $projectInterested;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($keyword =null)
    {
        $take = config('constants.project.interested', 9);
        $keyword = $keyword;

        $this->projectInterested = Project::select('project.*')
            ->orderByRaw(DB::raw('CASE WHEN project.project_name LIKE "%' . $keyword . '%" THEN 0 ELSE 1 END'))
            ->showed()
            ->latest('created_at')
            ->take($take)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.project.project-interested');
    }
}
