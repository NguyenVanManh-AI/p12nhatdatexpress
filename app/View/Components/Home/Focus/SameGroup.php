<?php

namespace App\View\Components\Home\Focus;

use App\Services\FocusService;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class SameGroup extends Component
{
    public $list;
    public $group_url;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request, $groupId, $groupUrl)
    {
        $this->group_url = $groupUrl;

        $focusService = new FocusService;

        $queries = $request->all();
        $queries['limit'] = config('constants.focus-news.same-group.limit', 9);
        $queries['group_id'] = $groupId;

        $this->list = $focusService->getListFromQuery($queries);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.same-group');
    }
}
