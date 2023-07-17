<?php

namespace App\View\Components\Home;

use App\Models\Province;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class HighLightArea extends Component
{
    public $top_1;
    public $top_4;
    public $top_10;
    public $post_fake;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->post_fake = optional(DB::table('system_config')->select('post_fake')->first())->post_fake;
        $top_10 = Province::select('province.*', DB::raw('count(province_id) as classified_num'))
            ->rightJoin('classified_location', 'province.id', '=', 'classified_location.province_id')
            ->groupBy('province.id', 'province_code', 'province_name', 'province_url', 'image_url', 'region_id', 'is_show', 'province_type')
            ->latest('classified_num')
            ->take(10)
            ->get();

        if (count($top_10->pluck('id')) < 10){
            $append = Province::whereNotIn('id', $top_10->pluck('id'))
                ->select('province.*',DB::raw('0 as classified_num'))
                ->latest('province_type')
                ->take(10 - count($top_10))
                ->get();
            $top_10 = $top_10->merge($append);
        }

        $this->top_1 = $top_10->shift();
        $this->top_4 = $top_10->shift(4);
        $this->top_10 = $top_10;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.high-light-area');
    }
}
