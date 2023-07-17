<?php

namespace App\View\Components\Home;

use App\Models\Province;
use Illuminate\View\Component;

class GlobalTag extends Component
{
    public $provinces;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->provinces = Province::select('province_name', 'province_type', 'province_url')
            ->showed()
            ->latest('province_type')
            ->oldest('province_name')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.global-tag');
    }
}
