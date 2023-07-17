<?php

namespace App\Http\Controllers\Admin\Classified;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use App\Models\ServiceFee;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    private $service_fee_id = [1,2,3,4,5,6];
    private $config_id = ['N006','N007','C010','C011','C012','C014', 'D001'];

    public function get_setup(){
        $notes = AdminConfig::whereIn('config_code', $this->config_id)->get();
        $services_fee = ServiceFee::whereIn('id', $this->service_fee_id)->get();

        return view('Admin.Classified.Setup.Setup',compact('notes', 'services_fee'));
    }
    public function update(Request $request){
        foreach ($this->config_id as $item){
            $config = AdminConfig::firstWhere('config_code', $item);
            if (!$config) continue;
            // update note
            $config->update([
                'config_value' =>  $request->$item ?? ''
            ]);
            // Helper::create_admin_log(47,[
            //     'config_value' =>  $request->$item ?? ''
            // ]);
        }

        foreach ($this->service_fee_id as $item){
            $name = "service_" . $item;

            $serviceFee = ServiceFee::find($item);
            if (!$serviceFee) continue;

            $serviceFee->update([
               'service_coin' => $request->$name ?? 0
            ]);
            // Helper::create_admin_log(47,[
            //     'service_coin' =>  $request->$name ?? 0
            // ]);
        }

        Toastr::success('Thành công');
        return back();
    }
}
