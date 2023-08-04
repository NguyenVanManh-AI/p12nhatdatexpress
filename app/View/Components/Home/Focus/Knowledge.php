<?php

namespace App\View\Components\Home\Focus;

use App\Models\Group;
use App\Services\FocusService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Knowledge extends Component
{
    public $ads;
    public $list;
    public $group;
    public $children_group;
   
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->group = Group::find(53); // kien thuc bat dong san
        $this->children_group = new Collection([]);
        if ($this->group) {
            $this->children_group = $this->group->children()->get();
        }

        // get ads
        $focusService = new FocusService;
        $adsQueries = $request->all();
        $adsQueries['get_first'] = true;
        $adsQueries['is_ad'] = config('constants.focus-news.news.ad', 1);
        $this->ads = $focusService->getListFromQuery($adsQueries);

        // get knowledge
        $knowledgeQueries = $request->all();
        $knowledgeQueries['limit'] = config('constants.focus-news.news.knowledge', 15);
        $knowledgeQueries['not_ids'] = $this->ads ? [$this->ads->id] : [];
        $knowledgeQueries['group_id'] = request('knowledge') ?? 54;
        $this->list = $focusService->getListFromQuery($knowledgeQueries);
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
