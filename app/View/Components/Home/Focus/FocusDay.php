<?php

namespace App\View\Components\Home\Focus;

use App\Services\FocusService;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class FocusDay extends Component
{
    public $list;
    public $top_1;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $focusService = new FocusService;

        $queries = $request->all();
        // $queries['limit'] = config('constants.focus-news.focus_day.limit', 5);
        $queries['get_all'] = true;
        $queries['sort_num_view'] = true;
        $queries['start_date'] = now()->format('Y-m-d 00:00:01');
        $queries['end_date'] = now()->format('Y-m-d 23:59:59');

        $this->list = $focusService->getListFromQuery($queries);
        $this->top_1 = $this->list->shift();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.focus-day');
    }
}
