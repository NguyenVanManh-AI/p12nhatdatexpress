<?php

namespace App\View\Components\Home;

use App\Models\Group;
use App\Models\Project;
use Illuminate\View\Component;

class BannerTopMobile extends Component
{
    public $home_config;
    // public $group;
    // public $project;
    public $directions;
    public $categories;
    public $classified_sell_search_price;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($homeConfig, $directions)
    {
        $this->home_config = $homeConfig;
        // $this->group = Group::where('id', '>', 1)->where('id', '<=', 34)->whereNull('parent_id')->get();
        // $this->project = Project::all(['id', 'project_name']);
        $this->categories = Group::select('group_name', 'group_url')->find([2, 10, 34]); // nha dat ban, nha dat cho thue, du an
        $this->directions = $directions;
        $this->classified_sell_search_price = config('constants.classified.search.price.sell', []);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.banner-top-mobile');
    }
}
