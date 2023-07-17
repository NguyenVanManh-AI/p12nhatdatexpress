<?php

namespace App\View\Components\Home\Classified;

use Illuminate\View\Component;

class ContentMain extends Component
{
    public $item;
    public $group;
    public $key_search;
    public $url;
    public $disableAction;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $group, $keySearch, $disableAction = false)
    {
        $this->item = $item;
        $this->group = $group;
        $this->key_search = $keySearch;
        $this->disableAction = $disableAction ? true : false;

        if ($item->group_parent_parent_url){
            $this->url = route('home.classified.list', [$this->item->group_parent_parent_url, $this->item->group_url]);
        } elseif ($item->group_parent_url){
            $this->url = route('home.classified.list', [$this->item->group_parent_url, $this->item->group_url]);
        } else{
            $this->url = route('home.classified.list', $this->item->group_url);
        }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.classified.content-main');
    }
}
