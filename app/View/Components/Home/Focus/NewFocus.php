<?php

namespace App\View\Components\Home\Focus;

use App\Services\FocusService;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class NewFocus extends Component
{
    public $news;
    public $itemsPerPage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $focusService = new FocusService;

        $queries = $request->all();
        $this->itemsPerPage = config('constants.focus-news.news.items_per_page', 9);
        $queries['items_per_page'] = $this->itemsPerPage;

        $this->news = $focusService->getListFromQuery($queries);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.new-focus');
    }
}
