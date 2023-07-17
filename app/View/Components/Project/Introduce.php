<?php

namespace App\View\Components\Project;

use App\Models\AdminConfig;
use Illuminate\View\Component;

class Introduce extends Component
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
        $introduce1 = AdminConfig::where('config_code','C002')->first();
        $introduce2 = AdminConfig::where('config_code','C003')->first();

        $introduce1 = str_replace("<span class='text-danger'>%vị_trí</span>", "<span class='text-danger'>#dự_án</span>", $introduce1->config_value);
        $introduce2 = str_replace("<span class='text-danger'>%vị_trí</span>", "<span class='text-danger'>#dự_án</span>", $introduce2->config_value);
        return view('components.project.introduce',['introduce1'=>$introduce1,'introduce2'=>$introduce2]);
    }
}
