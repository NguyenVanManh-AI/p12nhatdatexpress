<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class SocialLink extends Component
{
    public $socials;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($socials = [])
    {
        $this->socials = $socials;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.social-link');
    }
}
