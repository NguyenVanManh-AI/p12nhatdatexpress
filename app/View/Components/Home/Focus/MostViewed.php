<?php

namespace App\View\Components\Home\Focus;

use App\Services\FocusService;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class MostViewed extends Component
{
    public $list;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $focusService = new FocusService;

        $queries = $request->all();
        $queries['limit'] = config('constants.focus-news.most_viewed.limit', 5);
        $queries['sort_num_view'] = true;

        $this->list = $focusService->getListFromQuery($queries);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.most-viewed');
    }
}
