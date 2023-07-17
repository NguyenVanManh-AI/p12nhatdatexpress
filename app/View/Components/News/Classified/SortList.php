<?php

namespace App\View\Components\News\Classified;

use Illuminate\View\Component;

class SortList extends Component
{
    public $sortLists;
    public $selectedSort;
    /**
     * Create a new component instance.
     * @param $selectedText = null
     *
     * @return void
     */
    public function __construct($selectedText = null)
    {
        $this->sortLists = config('constants.classified.search.sort', []);
        $this->selectedSort = $selectedText ?: '';
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        foreach ($this->sortLists as $sortList) {
            if (data_get($sortList, 'value') == request()->sort) {
                $this->selectedSort = data_get($sortList, 'label');
                break;
            }
        }
        return view('components.news.classified.sort-list', [
            'selectedSort' => $this->selectedSort
        ]);
    }
}
