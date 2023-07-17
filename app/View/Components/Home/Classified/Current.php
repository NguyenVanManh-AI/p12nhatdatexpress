<?php

namespace App\View\Components\Home\Classified;

use App\Models\Classified\Classified;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Current extends Component
{
    public $list;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $watched_classified = getWatchedClassifieds();
        // $classified = new Collection();
        $array =array_reverse($watched_classified);

        // should fix n+1
        // foreach (array_splice($array,0,8) as $i) {
            $this->list = Classified::query()
                ->with('group.parent', 'location.province', 'location.district', 'unit_area', 'unit_price')
                ->leftJoin('group','classified.group_id','group.id')
                ->leftJoin('group as group_parent','group.parent_id','group_parent.id')
                ->leftJoin('group as group_parent_parent','group_parent.parent_id','group_parent_parent.id')
                ->select(
                    'group.group_name', 'group.group_url','group_parent.id as group_parent_id','group_parent_parent.id as group_parent_parent_id',
                    'classified.*',
                    'group_parent.group_url as group_parent_url',
                    'group_parent_parent.group_url as group_parent_parent_url',
                )
                ->showed()
                ->find(array_splice($array, 0, 8));
        //     if($item){
        //         $classified->push($item);
        //     }
        // }

        // $this->list = $classified;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.classified.current');
    }
}
