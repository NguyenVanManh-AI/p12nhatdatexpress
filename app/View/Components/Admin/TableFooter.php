<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class TableFooter extends Component
{
    public $checkRole;
    public $lists;
    public $deleteUrl;
    public $updateUrl;
    public $duplicateUrl;
    public $forceDeleteUrl;
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
        $checkRole = null,
        $lists,
        $deleteUrl = null,
        $updateUrl = null,
        $duplicateUrl = null,
        $forceDeleteUrl = null,
        $restoreUrl = null,
        $viewTrashUrl = null,
        $countTrash = null,
        $hideAction = false
    ) {
        $this->checkRole = $checkRole;
        $this->lists = $lists;
        $this->deleteUrl = $deleteUrl;
        $this->updateUrl = $updateUrl;
        $this->duplicateUrl = $duplicateUrl;
        $this->forceDeleteUrl = $forceDeleteUrl;
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
        return view('components.admin.table-footer');
    }
}
