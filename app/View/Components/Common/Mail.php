<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class Mail extends Component
{
    public $mailAsArray;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($mail)
    {
        $this->mailAsArray = explode('@', $mail);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.mail');
    }
}
