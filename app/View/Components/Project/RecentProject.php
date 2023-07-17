<?php

namespace App\View\Components\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class RecentProject extends Component
{
    public $list;
    public $group;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($group)
    {
        $location = Session::get('latLng',null);


        $project = Project::leftJoin('project_location','project_location.project_id','=','project.id')
            ->with('unit_sell:id,unit_name', 'unit_area:id,unit_name', 'location.province:id,province_name,province_type', 'location.district:id,district_name,district_type', 'unitArea')
            ->select('project.id', 'project_name', 'project_url', 'image_thumbnail', 'project_price_old', 'project_area_from', 'num_view',
                'project_price', 'project_rent_price',
                'project_unit_id', 'area_unit_id', 'price_unit_id')
            ->where('project.is_deleted','=',0);
        if($location){
            $this->selectNear($project, $location);
        }else{
            $project->orderBy('num_view', 'desc');
        }

        $this->list = $project->take(6)->get();
        $this->group = $group;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.project.recent-project');
    }


    public function selectNear($query, $location) {
        $haversine = "(round(6371 * acos(cos(radians({$location['lat']}))
                     * cos(radians(project_location.map_latitude))
                     * cos(radians(project_location.map_longtitude)
                     - radians({$location['lng']}))
                     + sin(radians({$location['lat']}))
                     * sin(radians(project_location.map_latitude)))))";
        return $query
            ->selectRaw("{$haversine} AS distance")
            ->orderBy('distance');
    }
}
