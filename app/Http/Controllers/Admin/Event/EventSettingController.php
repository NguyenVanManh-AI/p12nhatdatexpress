<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventSettingController extends Controller
{
    public function edit(){
        return view('Admin.Event.EventSetting');
    }
}
