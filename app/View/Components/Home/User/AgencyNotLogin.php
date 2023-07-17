<?php

namespace App\View\Components\Home\User;

use Illuminate\View\Component;

class AgencyNotLogin extends Component
{
    public $item;
    public $showAction;
    public $advisoryUrl;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $item,
        $advisoryUrl = null,
        $showAction = true
    ) {
        $this->item = $item;
        $this->advisoryUrl = $advisoryUrl;
        $this->showAction = $showAction;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.user.agency-not-login');
    }
}
