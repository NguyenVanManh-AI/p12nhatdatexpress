<?php

namespace App\View\Components\Home\Project;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class UpdateProject extends Component
{
    public $progress;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->progress = DB::table('progress')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.project.update-project');
    }
}
