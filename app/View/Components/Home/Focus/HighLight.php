<?php

namespace App\View\Components\Home\Focus;

use App\Services\FocusService;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class HighLight extends Component
{
    public $top_1;
    public $list;
    public $group;
    public $is_special_group = 0;
    public $is_hidden = 0;

    private const GROUP = [
        'SPECIAL' => 48,
        'EXPRESS' => 49
    ];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($group, Request $request)
    {
        if ($group->id == self::GROUP['SPECIAL']){ //  sự kiện nổi bật
            $this->is_hidden = 1;
        }

        $focusService = new FocusService;

        $queries = $request->all();
        $queries['group_id'] = self::GROUP['EXPRESS'];

        if ($group->id == self::GROUP['EXPRESS']){ // || $group->id == 48
            $this->is_special_group = 1;
            $queries['limit'] = config('constants.focus-news.express.highlight_limit', 2);
        } else {
            $queries['limit'] = config('constants.focus-news.child.highlight_limit', 20);
        }

        $this->list = $focusService->getListFromQuery($queries);

        if ($group->id != self::GROUP['EXPRESS']) {
            $this->top_1 = $this->list->shift();
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.high-light');
    }
}
