<?php

namespace App\View\Components\Home\Focus;

use App\Models\Group;
use App\Services\FocusService;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Furniture extends Component
{
    public $list;
    public $group;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->group = Group::find(51); // kien truc noi that

        $focusService = new FocusService;
        $queries = $request->all();
        $queries['limit'] = config('constants.focus-news.news.furniture', 7);
        $queries['group_id'] = 51;
        $this->list = $focusService->getListFromQuery($queries);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.furniture');
    }
}
