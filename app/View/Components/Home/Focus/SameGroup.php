<?php

namespace App\View\Components\Home\Focus;

use App\Models\News;
use Illuminate\Support\Facades\DB;
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
    public function __construct($groupId, $groupUrl)
    {
        $this->group_url = $groupUrl;

        $this->list = News::select('news.*', 'group.id as group_id', 'group.group_name', 'group.group_url')
            ->where('news.group_id', $groupId)
            ->showed()
            ->orderBy('news.id', 'desc')
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->take(9)
            ->get();
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
