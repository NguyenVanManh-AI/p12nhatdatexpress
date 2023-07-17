<?php

namespace App\View\Components\News\Home;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class BannerTop extends Component
{
    public $home_config;
    public $directions;
    public $group; 
    public $classified_sell_search_price;
    public $provinces;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->home_config = DB::table('home_config')->orderBy('id', 'desc')->first(['header_text_block', 'desktop_header_image', 'mobile_header_image']);
        $this->directions = DB::table('direction')->select('direction_name', 'id')->where('is_show', 1)->get();
        $this->group = DB::table('group')->where('parent_id', 2)->get();
        $this->classified_sell_search_price = config('constants.classified.search.price.sell', []);
        $this->provinces = get_cache_province();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news.home.banner-top');
    }
}
