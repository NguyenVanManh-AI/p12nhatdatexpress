<?php

namespace App\View\Components\Home;

use App\Models\Event\Event as EventModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Event extends Component
{
    public $events;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->events = EventModel::with('location.province', 'location.district')
        ->showed()
        ->orderBy('is_highlight', 'desc')
        ->orderBy('start_date', 'asc')
        ->take(4)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.home.event', [
            'events' => $this->events
        ]);
    }
}
