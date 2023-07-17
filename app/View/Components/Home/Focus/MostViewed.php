<?php

namespace App\View\Components\Home\Focus;

use App\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class MostViewed extends Component
{
    public $list;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->list = News::select('news.*', 'group.id as group_id', 'group.group_name', 'group.group_url')
            ->showed()
            ->orderBy('news.num_view', 'desc')
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->take(5)
            ->get();
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
