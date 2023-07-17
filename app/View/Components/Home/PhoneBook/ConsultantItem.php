<?php

namespace App\View\Components\Home\PhoneBook;

use Illuminate\View\Component;

class ConsultantItem extends Component
{
    public $item;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.phone-book.consultant-item');
    }
}
