<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class TableFooter extends Component
{
    public $lists;
    public $deleteUrl;
    public $restoreUrl;
    public $viewTrashUrl;
    public $countTrash;
    public $hideAction;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $lists,
        $deleteUrl = null,
        $restoreUrl = null,
        $viewTrashUrl = null,
        $countTrash = null,
        $hideAction = false
    ) {
        $this->lists = $lists;
        $this->deleteUrl = $deleteUrl;
        $this->restoreUrl = $restoreUrl;
        $this->viewTrashUrl = $viewTrashUrl;
        $this->countTrash = $countTrash;
        $this->hideAction = $hideAction;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.table-footer');
    }
}
