<?php

namespace App\View\Components\Home\Focus;

use App\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Relate extends Component
{
    public $list;
    public $focus;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($focus)
    {
        $this->focus = $focus;
        $this->get_relate_list($focus);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.relate');
    }

    /**
     * Get relate list
     * @param $focus
     * @return void
     */
    public function get_relate_list($focus){
        $this->list = collect([]);
        $ids = collect($focus->id);

        foreach (explode(',', $focus->tag_list) as $tag){
            $news_has_tags = News::select('news.*', 'group.id as group_id', 'group.group_name', 'group.group_url')
                ->showed()
                ->whereNotNull('news.tag_list')
                ->whereNotIn('news.id', [$ids])
                ->where('news.tag_list', 'like', "%$tag%")
                ->leftJoin('group', 'group.id', '=', 'news.group_id')
                ->orderBy('news.created_at', 'desc')
                ->take(5)
                ->get();

            $news_has_tags->contains(function ($value) use ($tag , $ids){
                $check = collect(explode(',', $value->tag_list))->contains($tag);
                if ($check){
                    $this->list->push($value);
                    $ids->push($value->id);
                }
            });
        }

    }
}
