<?php

namespace App\View\Components\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class EditButton extends Component
{
    public $isButton;
    public $url;
    public $item;
    public $haveAccess;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $checkRole,
        $isButton = false,
        $url,
        $item = null
    ) {
        $roleId = Auth::guard('admin')->user()->rol_id;
        $adminId = Auth::guard('admin')->user()->id;

        $actionKey = 2; // update
        // check permission
        $this->haveAccess = $checkRole === 1 ? true : false;
        if (!$this->haveAccess && key_exists($actionKey, $checkRole)) {
            if (key_exists('all', $checkRole[$actionKey])) {
                $this->haveAccess = true;
            } else if (key_exists('group', $checkRole[$actionKey])) {
                $this->haveAccess = $item && $roleId == data_get($item->createdBy, 'rol_id');
            } else if (key_exists('self', $checkRole[$actionKey])) {
                $this->haveAccess = $item && $item->created_by == $adminId;
            } else if (key_exists('check', $checkRole[$actionKey])) {
                $this->haveAccess = true;
            }
        }

        $this->isButton = $isButton;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.edit-button');
    }
}
