<?php

namespace App\View\Components\User\Index;

use App\Models\Project;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeployingProject extends Component
{
    public $listProjects;
    public $selectedProjects;
    public $provinces;
    public $districts;
    public $wards;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listProjects = Project::select('id', 'project_name')
            ->showed()
            ->get();

        $user = Auth::guard('user')->user();
        $this->selectedProjects = $user->projects()->allRelatedIds()->toArray();

        $this->provinces = DB::table('province')->select('id', 'province_name')->get();
        $this->districts = DB::table('district')->select('id', 'district_name')->where('province_id', old('pr_province'))->get();
        $this->wards = DB::table('ward')->select('id', 'ward_name')->where('district_id', old('pr_district'))->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.index.deploying-project');
    }
}
