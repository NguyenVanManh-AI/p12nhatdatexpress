<?php

namespace App\View\Components\Home\Classified;

use App\Models\Classified\Classified;
use App\Services\Classifieds\ClassifiedService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class Near extends Component
{
    public $list;
    public $group;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($group=null)
    {
        $this->group = $group;

        $classified = Classified::leftJoin('classified_location','classified_location.classified_id','=','classified.id')
            ->with('unit_area:id,unit_name', 'location.province:id,province_name,province_type', 'location.district:id,district_name,district_type', 'unit_price')
            ->select('classified.id', 'classified_name', 'classified_url', 'image_perspective', 'num_view', 'classified_price',
                'price_unit_id', 'area_unit_id',
                'classified_area',
                'group_parent.group_url as group_parent_url', 'group_parent_parent.group_url as group_parent_parent_url')
            ->leftJoin('group','group.id','=','classified.group_id')
            ->leftJoin('group as group_parent','group_parent.id','=','group.parent_id')
            ->leftJoin('group as group_parent_parent','group_parent_parent.id','=','group_parent.parent_id')
            ->showed()
            ->where(function ($query) {
                $query->where('group_parent_parent.id','=',$this->group)
                    ->orWhere('group_parent.id','=',$this->group);
                });

        $classified = (new ClassifiedService())->selectNear($classified, true);

        $this->list = $classified->latest('num_view')
            ->take(5)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.classified.near');
    }

    /**
     * Search near func
     * @param $query
     * @param $location
     * @return mixed
     */
    public function selectNear($query, $location) {
        if ($location['lat'] && $location['lng']){
            $haversine = "(round(6371 * acos(cos(radians({$location['lat']}))
                     * cos(radians(classified_location.map_latitude))
                     * cos(radians(classified_location.map_longtitude)
                     - radians({$location['lng']}))
                     + sin(radians({$location['lat']}))
                     * sin(radians(classified_location.map_latitude)))))";
            return $query
                ->selectRaw("{$haversine} AS distance")
                ->orderBy('distance');
        }
    }
}
