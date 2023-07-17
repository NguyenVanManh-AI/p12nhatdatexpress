<?php

namespace App\View\Components\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Notify extends Component
{
    public $notifies;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = Auth::guard('user')->user();

        $this->notifies = $user->notifies()
            ->latest()
            ->status()
            ->read(false)
            ->take(10)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.notify');
    }
}
