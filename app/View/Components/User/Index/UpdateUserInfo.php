<?php

namespace App\View\Components\User\Index;

use App\Models\User\UserDetail;
use App\Models\User\UserLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class UpdateUserInfo extends Component
{
    public $user_detail_info;
    public $user_location_info;
    public $provinces;
    public $districts;
    public $wards;
    public $projects;
    public $current_project;

    public function __construct()
    {

    }


    public function render()
    {
        return view('components.user.index.update-user-info');
    }
}
