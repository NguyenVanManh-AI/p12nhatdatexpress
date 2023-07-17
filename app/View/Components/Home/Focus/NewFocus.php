<?php

namespace App\View\Components\Home\Focus;

use App\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class NewFocus extends Component
{
    public $num_collection;
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
            ->orderBy('news.created_at', 'desc')
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->paginate(9);

        $this->num_collection = collect(['num_cur' => $this->list->currentPage() * 9]);
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
