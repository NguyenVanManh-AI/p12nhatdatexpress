<?php

namespace App\View\Components\Home\Focus;

use App\Models\Group;
use App\Services\FocusService;
use App\Traits\Filterable;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Fengshui extends Component
{
    use Filterable;

    public $list;
    public $group;
    public $top_1;

    protected $table = 'news';
    protected $filterable = [
        'keyword'
    ];
    /**
     * Filter keyword
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterKeyword($query, $value)
    {
        return $query->where($this->table . '.' . 'news_title', 'like', "%$value%");
    }

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->group = Group::find(52); // phong thuy

        $focusService = new FocusService;
        $queries = $request->all();
        $queries['limit'] = config('constants.focus-news.news.property', 20);
        $queries['group_id'] = 52;

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
        return view('components.home.focus.fengshui');
    }
}
