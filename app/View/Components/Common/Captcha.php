<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class Captcha extends Component
{
    public $showError;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($showError = true)
    {
        $this->showError = $showError;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.captcha');
    }
}
