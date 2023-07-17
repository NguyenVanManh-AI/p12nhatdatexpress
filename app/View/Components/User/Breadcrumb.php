<?php

namespace App\View\Components\User;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $activeLabel;
    public $parents;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($activeLabel, $parents = [['label' => 'Thành viên', 'route' => 'user.index']])
    {
        $this->activeLabel = $activeLabel;
        $this->parents = $parents;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.breadcrumb');
    }
}
