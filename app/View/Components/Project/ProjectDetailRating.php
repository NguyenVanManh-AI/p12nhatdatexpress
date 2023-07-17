<?php

namespace App\View\Components\Project;

use Illuminate\View\Component;
use DB;

class ProjectDetailRating extends Component
{   
    public $message;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {   
        $oneStar = DB::table('project_rating')->where('project_id',$this->message)->where('one_star',1)->count();
        $twoStar = DB::table('project_rating')->where('project_id',$this->message)->where('two_star',1)->count();
        $threeStar = DB::table('project_rating')->where('project_id',$this->message)->where('three_star',1)->count();
        $fourStar = DB::table('project_rating')->where('project_id',$this->message)->where('four_star',1)->count();
        $fiveStar = DB::table('project_rating')->where('project_id',$this->message)->where('five_start',1)->count();
        $countRating = DB::table('project_rating')->where('project_id',$this->message)->count();

        $project_id = $this->message;
        
        return view('components.project.project-detail-rating',compact('oneStar','twoStar','fourStar','threeStar','fiveStar','project_id','countRating'));
    }
}
