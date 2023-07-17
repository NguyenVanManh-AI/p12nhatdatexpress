<?php

namespace App\View\Components\Project;

use App\Models\Admin\ProjectLocation;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class HightLink extends Component
{
    private const MAX_LINK=18;
    public $getHightLink;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->getHightLink = ProjectLocation::with('district:id,district_name,district_url,province_id,district_type')
            ->join('project as p', 'p.id', '=', 'project_location.project_id')
            ->join('group as g', 'g.id', '=', 'p.group_id')
            ->select(DB::raw('count(*) as count'), 'district_id', 'g.group_name', 'g.group_url')
            ->groupBy('district_id', 'g.group_name', 'g.group_url')
            ->orderBy('count', 'desc')
            ->take(self::MAX_LINK)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.project.hight-link');
    }
}
