<?php

namespace App\View\Components\Home\Focus;

use App\Models\News;
use Illuminate\Support\Facades\DB;
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
    public function __construct($group)
    {
        $user_id = auth('user')->id();
        $this->list = News::select('news.*', 'group.id as group_id', 'group.group_name', 'group.group_url', 'a.admin_fullname')
            ->showed()
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->leftJoin('admin as a', 'a.id', '=', 'news.created_by');

        if ($user_id){
            $this->list = $this->list->addSelect('nk.type as like_type')
                ->leftJoin('news_like as nk', function ($query) use ($user_id){
                $query->on('nk.news_id', '=', 'news.id')
                    ->where('user_id', $user_id);
            });
        }

        if ($group->id == self::GROUP['SPECIAL']){ //  sự kiện nổi bật
            $this->is_hidden = 1;
        }
        if ($group->id == self::GROUP['EXPRESS']){ // || $group->id == 48
            $this->is_special_group = 1;
            $this->list = $this->list->where('news.is_express', 1)
                ->orderBy('news.created_at', 'desc')
                ->take(2)
                ->get();
        }
        else{
            $this->list = $this->list->where('news.is_express', 1)
                ->orderBy('news.created_at', 'desc')
                ->take(20)
                ->get();
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
