<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class SetupController extends Controller
{
    private $ids = ['N008', 'N011', 'N012'];

    # Show configs
    public function get_setup(){
        $notes = AdminConfig::whereIn('config_code', $this->ids)->get();

        return view('Admin.User.Setup',compact('notes'));
    }

    # Store configs
    public function update(Request $request){
        foreach ($this->ids as $item){
            $config = AdminConfig::firstWhere('config_code', $item);
            if (!$config) continue;

            $config->update([
                'config_value' =>  $request->$item ?? ''
            ]);
        }
        // $data = [
        //     'N008'=>$request->$item ?? ''
        // ];
        // Helper::create_admin_log(167,$data);

        Toastr::success('Thành công');
        return back();
    }
}
