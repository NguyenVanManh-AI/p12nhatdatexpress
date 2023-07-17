<?php

namespace App\View\Components\Project;

use Illuminate\View\Component;
use DB;

class Search extends Component
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
        $getGroup = DB::table("group")->where('parent_id',34)->where('is_deleted',0)->get();
        $getProvince = DB::table("province")->where('is_show',1)->get();

        $getGroup = DB::table("group")->where('parent_id',34)->where('is_deleted',0)->get();
        $directions = DB::table("direction")->get();
        $utility=DB::table("utility")->get();
        return view('components.project.search',['getGroup'=>$getGroup,'getProvince'=>$getProvince,'directions'=>$directions,'utility'=>$utility]);
    }
}
