<?php

namespace App\View\Components\Home\Focus;

use App\Models\News;
use Illuminate\Support\Facades\DB;
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
    public function __construct()
    {
        $this->list = News::select('news.*', 'group.id as group_id', 'group.group_name', 'group.group_url')
            ->whereDate(DB::raw('from_unixtime(news.created_at)'), today())
            ->showed()
            ->orderBy('news.num_view', 'desc')
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
//            ->take(5)
            ->get();
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
