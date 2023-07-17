<?php

namespace App\View\Components\User\Index;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserInfoStatus extends Component
{
    public $user;
    public $user_info;

    public function __construct()
    {
        $this->user = Auth::guard('user')->user();
        $this->user_info = DB::table('user as u')
            ->select('u.is_forbidden','u.phone_number', 'u.email','u.is_deleted', 'u.delete_time', 'ul.province_id', 'ul.district_id', 'ul.ward_id')
            ->leftJoin('user_location as ul', 'u.id', '=','ul.user_id')
            ->where('u.id', $this->user->id)
            ->where(function ($query){
                $query->WhereNull('u.phone_number')
                    ->orWhereNull('u.email')
                    ->orWhereNull('ul.district_id')
                    ->orWhereNull('ul.ward_id')
                    ->orWhereNull('ul.province_id');
                    // ->orWhere('u.is_deleted',1);
            })
            ->first();
}


    public function render()
    {
        return view('components.user.index.user-info-status');
    }
}
