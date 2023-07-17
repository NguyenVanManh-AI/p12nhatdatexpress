<?php

namespace App\View\Components\Home\User;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class LoginPopup extends Component
{
    public $provinces;
    public $districts;
    public $wards;
    public $registerText;

    public function __construct()
    {
        $this->provinces = DB::table('province')
            ->select('id', 'province_name')
            ->get();

        $this->districts = DB::table('district')
            ->select('id', 'district_name')
            ->where('province_id', old('province'))
            ->get();

        $this->wards = DB::table('ward')
            ->select('id', 'ward_name')
            ->where('district_id', old('district'))
            ->get();

        $this->registerText = DB::table('admin_config')
            ->select('config_code', 'config_value')
            ->whereIn('config_code', ['N008', 'N011', 'N012'])
            ->get();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.user.login-popup');
    }
}
