<?php

namespace App\View\Components\Home\Project;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ContentProject extends Component
{
    public $project;
    public $properties;
    public $is_preview;
    public $utility_list;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($project, $properties, $is_preview = false)
    {
        $this->project = $project;
        $this->properties = $properties;
        $this->ispreview = $is_preview;
        $this->utility_list = $this->get_utility_list();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.project.content-project');
    }

    public function get_utility_list():array {
        $utility_list = json_decode($this->project->list_utility);
        if (is_array($utility_list) && count($utility_list) > 0)
            return DB::table('utility')->whereIn('id', $utility_list)->get()->values()->all();
        else
            return [];
    }
}
