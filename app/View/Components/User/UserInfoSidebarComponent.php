<?php

namespace App\View\Components\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class UserInfoSidebarComponent extends Component
{
    public $user_info;
    public function __construct()
    {
        $user = Auth::guard('user')->user();
        $this->user_info = DB::table('user as u')
            ->select('d.fullname', 'd.image_url','ty.type_name', 'e.level_name')
            ->where('u.id', $user->id)
            ->leftJoin('user_detail as d', 'u.id', '=', 'd.user_id')
            ->leftJoin('user_level as e', 'u.user_level_id', '=' , 'e.id')
            ->leftJoin('user_type as ty', 'u.user_type_id', '=', 'ty.id')
            ->first();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.user-info-sidebar-component');
    }
}
