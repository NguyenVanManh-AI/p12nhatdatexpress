<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class ForceDeleteButton extends Component
{
    public $checkRole;
    public $id;
    public $isButton;
    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $checkRole,
        $id,
        $isButton = false,
        $url
    ) {
        $this->checkRole = $checkRole;
        $this->id = $id;
        $this->isButton = $isButton;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.force-delete-button');
    }
}
