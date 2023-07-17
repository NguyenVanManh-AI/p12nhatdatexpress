<?php

namespace App\View\Components\Home;

use App\Models\News;
use Illuminate\View\Component;

class Focus extends Component
{
    public $focus;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->focus = News::select('news.group_id', 'news.news_title', 'news.news_description', 'news.news_url', 'news.image_url', 'news.created_at',
                'group.id as group_id', 'group.group_name', 'group.group_url')
            ->where('news.is_show',1)
            ->orderBy('news.created_at', 'desc')
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->take(20)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus');
    }
}
