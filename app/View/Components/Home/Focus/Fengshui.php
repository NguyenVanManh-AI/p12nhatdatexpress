<?php

namespace App\View\Components\Home\Focus;

use App\Helpers\Helper;
use App\Models\News;
use App\Traits\Filterable;
use Illuminate\Support\Facades\DB;
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
    public function __construct()
    {
        $this->group = DB::table('group')
            ->where('id', 52)
            ->first();

        $this->list = News::with('group')
            ->where('group_id', $this->group->id)
            ->showed()
            ->orderBy('is_highlight', 'desc')
            ->orderBy('created_at', 'desc');

        $params = Helper::array_remove_null(request()->all());
        $this->list = $this->scopeFilter($this->list, $params);

        $this->list = $this->list->take(5)->get();
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
