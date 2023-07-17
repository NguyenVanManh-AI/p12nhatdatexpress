<?php

namespace App\View\Components\Home;

use App\Models\Classified\Classified;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class UrgentSale extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $classified_view_week = DB::table('classified_view_week')->first();
        // slide show tin rao xem nhiều
        $classified_slideshow = Classified::with('group', 'group.parent', 'location.district', 'location.province', 'unit_area', 'unit_price')
            ->select(
                'classified.*',
            )
            ->where('num_view_today', '>', 0)
            ->latest('classified.num_view_today')
            ->showed()
            ->take(20)
            ->get();

            // if($classified_view_week){
            //     $array_list = unserialize($classified_view_week->classified_id);
            //     $classified_slideshow=$classified_slideshow->whereIn('classified.id',$array_list);
            // }

        return view('components.home.urgent-sale',compact('classified_slideshow'));
    }
}
