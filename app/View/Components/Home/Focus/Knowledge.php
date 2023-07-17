<?php

namespace App\View\Components\Home\Focus;

use App\Helpers\Helper;
use App\Models\News;
use App\Traits\Filterable;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Knowledge extends Component
{
    use Filterable;

    public $per_page = 15; // 15
    public $ads;
    public $list;
    public $group;
    public $children_group;
    // public $num_collection;

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
        return $query->where('news_title', 'like', "%$value%");
    }

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->group = DB::table('group')->where('id', 53)->first();
        $this->children_group = DB::table('group')->where('parent_id', 53)->get();
        if ($this->children_group->where('id', $this->group->id)->count() < 1){
            $this->group = DB::table('group')->where('id', 53)->first();
        }

        $this->ads = $this->list = News::select('news.*', 'g.id as group_id', 'g.group_name', 'g.group_url', 'is_express as is_ads')
            ->where('news.group_id', request('knowledge') ?? 54)
            ->express()
            ->latest('news.is_highlight')
            ->latest('news.created_at')
            ->leftJoin('group as g', 'g.id', '=', 'news.group_id')
            ->first();

        $this->list = News::select('news.*', 'g.id as group_id', 'g.group_name', 'g.group_url')
            ->with('group')
            ->where('news.group_id', request('knowledge') ?? 54)
            ->showed()
            ->where('news.is_express', 0)
            ->latest('news.is_highlight')
            ->latest('news.created_at')
            ->leftJoin('group as g', 'g.id', '=', 'news.group_id');

        $params = Helper::array_remove_null(request()->all());
        $this->list = $this->scopeFilter($this->list, $params);

        $this->list = $this->list->limit($this->per_page)->get();
        // $this->num_collection = collect(['num_cur' => $this->list->currentPage() * $this->per_page]);

        // if ($this->list->currentPage() > 1){
        //     Session::flash('scroll_to', '.content-main #knowledge');
        // }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.knowledge');
    }
}
