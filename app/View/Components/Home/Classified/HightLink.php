<?php

namespace App\View\Components\Home\Classified;

use App\Models\Classified\ClassifiedLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class HightLink extends Component
{
    private const MAX_LINK=18;
    public $max;
    public $link;
    public $group_link;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($group = null, $child = null)
    {
        $this->group_link = $group;
        $this->link = ClassifiedLocation::with('district:id,district_name,district_url,province_id,district_type')
            ->join('classified', 'classified.id', '=', 'classified_location.classified_id')
            ->join('group', 'group.id', '=', 'classified.group_id')
            ->leftJoin('group as g_parent','group.parent_id','=','g_parent.id')
            ->leftJoin('group as g_parent_parent','g_parent.parent_id','=','g_parent_parent.id')
            ->select(DB::raw('count(*) as count'), 'district_id','group.group_name','g_parent.id as group_parent_id','g_parent.group_url as group_parent_url','g_parent_parent.id as group_parent_parent_id','g_parent_parent.group_url as group_parent_parent_url', 'group.group_url')
            ->whereHas('classified', function ($query) {
                return $query->showed();
            })
            ->groupBy('district_id', 'group.group_name', 'group.group_url','group_parent_parent_id','group_parent_id','group_parent_parent_url','group_parent_url')
            ->orderBy('count', 'desc');
        if($group!=null && $child!=null){
            $this->link=$this->link ->where('group.parent_id',$group);
            if( $group ==18 ){
            $this->link=$this->link->orWhere('g_parent_parent.id',$group);
            }
            if($child == 19 || $child == 20){
            $this->link=$this->link->where('group.parent_id',$child);
            }
        }
        $this->link=$this->link->take(self::MAX_LINK)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.classified.hight-link');
    }
}
