<?php

namespace App\View\Components\Home\Focus;

use App\Helpers\Helper;
use App\Models\Group;
use App\Services\FocusService;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Property extends Component
{
    public $group;
    public $list;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->group = Group::find(50); // tin bat dong san

        $focusService = new FocusService;
        $queries = $request->all();
        $queries['limit'] = config('constants.focus-news.news.property', 20);
        $queries['group_id'] = 50;

        $this->list = $focusService->getListFromQuery($queries);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.property');
    }
}
