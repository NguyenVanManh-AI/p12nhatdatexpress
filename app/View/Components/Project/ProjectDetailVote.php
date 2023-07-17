<?php

namespace App\View\Components\Project;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ProjectDetailVote extends Component
{
    public $message;
    public $vote;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
        $project = Project::where('project.id',$message)->first();
        $this->vote = unserialize($project->project_servey)??null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $voteYes1= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',11)->where('is_verify',1)->count();
        $voteYes2= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',12)->where('is_verify',1)->count();
        $voteYes3= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',13)->where('is_verify',1)->count();
        $voteYes4= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',14)->where('is_verify',1)->count();
        $voteYes5= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',15)->where('is_verify',1)->count();
        $voteYes6= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',16)->where('is_verify',1)->count();

        $voteNo1= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',11)->where('is_verify',0)->count();
        $voteNo2= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',12)->where('is_verify',0)->count();
        $voteNo3= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',13)->where('is_verify',0)->count();
        $voteNo4= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',14)->where('is_verify',0)->count();
        $voteNo5= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',15)->where('is_verify',0)->count();
        $voteNo6= DB::table('project_servey')->where('project_id',$this->message)->where('project_param_id',16)->where('is_verify',0)->count();
        $project_id = $this->message;

        return view('components.project.project-detail-vote',compact('voteYes1','voteYes2','voteYes3','voteYes4','voteYes5','voteYes6','voteNo1','voteNo2','voteNo3','voteNo4','voteNo5','voteNo6','project_id'));
    }
}
