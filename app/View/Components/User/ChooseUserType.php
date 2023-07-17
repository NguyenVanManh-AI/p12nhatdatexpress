<?php

namespace App\View\Components\User;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ChooseUserType extends Component
{
    public $registerText;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->registerText = DB::table('admin_config')
            ->select('config_code', 'config_value')
            ->whereIn('config_code', ['N008', 'N011', 'N012'])
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.choose-user-type');
    }
}
