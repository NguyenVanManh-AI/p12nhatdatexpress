<?php

namespace App\View\Components\User\Index;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class UserInfo extends Component
{
    public $user_info;

    public function __construct()
    {
        $user = Auth::guard('user')->user();
        $this->user_info = DB::table('user as u')
            ->select('u.*', 'd.fullname', 'd.background_url','d.image_url','u.is_deleted', 'u.delete_time', 'ty.type_name', 'e.level_name', 'p.province_name', 't.district_name', 'w.ward_name')
            ->where('u.id', $user->id)
            ->leftJoin('user_detail as d', 'u.id', '=', 'd.user_id')
            ->leftJoin('user_type as ty', 'u.user_type_id', '=', 'ty.id')
            ->leftJoin('user_level as e', 'u.user_level_id', '=' , 'e.id')
            ->leftJoin('user_location as l', 'u.id', '=', 'l.user_id')
            ->leftJoin('province as p', 'l.province_id', '=', 'p.id')
            ->leftJoin('district as t', 'l.district_id', '=', 't.id')
            ->leftJoin('ward as w', 'l.ward_id', '=', 'w.id')
            ->first();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.index.user-info');
    }
}
