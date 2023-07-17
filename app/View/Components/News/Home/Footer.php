<?php

namespace App\View\Components\News\Home;

use App\Models\StaticPage;
use Illuminate\View\Component;

class Footer extends Component
{
    public $getConfig;
    public $getStaticMenu;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->getConfig = $config;
        $this->getStaticMenu = StaticPage::showed()->select('page_url', 'page_title')->orderBy('show_order','desc')->take(10)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news.home.footer');
    }
}
