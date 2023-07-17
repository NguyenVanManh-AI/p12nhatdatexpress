<?php

namespace App\View\Components\User\Index;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class PersonalInfo extends Component
{
    public $user_info;
    public $provinces;
    public $districts;
    public $wards;
    public function __construct()
    {
        $user = Auth::guard('user')->user();
        $this->user_info = DB::table('user as u')
            ->select('u.user_type_id', 'u.phone_number', 'u.phone_securiry', 'u.email','d.fullname', 'd.birthday', 'd.tax_number','d.website','d.intro', 'l.province_id', 'l.district_id', 'l.ward_id')
            ->where('u.id', $user->id)
            ->leftJoin('user_detail as d', 'u.id', '=', 'd.user_id')
            ->leftJoin('user_location as l', 'u.id', '=', 'l.user_id')
            ->first();
        $this->provinces = DB::table('province')->select('id', 'province_name')->get();
        $this->districts = DB::table('district')
            ->select('id', 'district_name')
            ->where('province_id', $this->user_info->province_id)
            ->get();
        $this->wards = DB::table('ward')
            ->select('id', 'ward_name')
            ->where('district_id', $this->user_info->district_id)
            ->get();
    }


    public function render()
    {
        return view('components.user.index.personal-info');
    }
}
