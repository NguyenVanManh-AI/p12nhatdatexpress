<?php

namespace App\View\Components\Home\Event;

use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class AddEvent extends Component
{
    public $provinces;
    public $districts;
    public $specialist_event_fee;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->provinces = get_cache_province();
        $this->districts = get_cache_district();
        $this->specialist_event_fee = get_fee_service(7);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.event.add-event');
    }
}
