<?php

namespace App\View\Components\Home\User;

use Illuminate\View\Component;

class AgencyDetail extends Component
{
    public $user;
    public $advisoryLabel;
    public $advisoryUrl;
    public $showAction;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $user,
        $advisoryLabel = 'Gửi thông tin',
        $advisoryUrl = null,
        $showAction = true
    ) {
        $this->user = $user;
        $this->advisoryLabel = $advisoryLabel;
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
        return view('components.home.user.agency-detail');
    }
}
