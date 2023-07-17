<?php

namespace App\View\Components\Home;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Fanpage extends Component
{
    public $system_config;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->system_config = DB::table('system_config')->select('facebook')->first();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.fanpage');
    }
}
