<?php

namespace App\View\Components\Home\Focus;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Ads extends Component
{
    public $url;
    public $image;
    public $script;
    private const CONFIG_CODE = ['IMAGE' => 'C015', 'IMAGE_URL' => 'C017' , 'ADS_SCRIPT' => 'C016'];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->image = optional(DB::table('admin_config')->where('config_code',self::CONFIG_CODE['IMAGE'])->select('config_value')->first())->config_value;
        if ($this->image)
            $this->url = optional(DB::table('admin_config')->where('config_code',self::CONFIG_CODE['IMAGE_URL'])->select('config_value')->first())->config_value;
        $this->script = optional(DB::table('admin_config')->where('config_code',self::CONFIG_CODE['ADS_SCRIPT'])->select('config_value')->first())->config_value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.focus.ads');
    }
}
