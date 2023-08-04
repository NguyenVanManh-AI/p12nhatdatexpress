<?php

namespace App\View\Components\Admin;

use App\Enums\AdminActionEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ActionButton extends Component
{
    public $isButton;
    public $url;
    public $item;
    public $haveAccess;
    public $action;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $checkRole,
        $isButton = false,
        $url,
        $item = null,
        $action
    ) {
        $roleId = Auth::guard('admin')->user()->rol_id;
        $adminId = Auth::guard('admin')->user()->id;

        $actionKey = null;
        switch ($action) {
            case AdminActionEnum::UPDATE:
                $actionKey = 2;
                break;
            case AdminActionEnum::DUPLICATE:
                $actionKey = 3;
                break;
            case AdminActionEnum::DELETE:
                $actionKey = 5;
                break;
            case AdminActionEnum::RESTORE:
                $actionKey = 6;
                break;
            case AdminActionEnum::FORCE_DELETE:
                $actionKey = 7;
                break;
            default:
                break;
        }

        // check permission
        $this->haveAccess = $checkRole === 1 ? true : false;

        if (!$this->haveAccess && $actionKey && key_exists($actionKey, $checkRole)) {
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

        $this->action = $action;
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
        return view('components.admin.action-button');
    }
}
