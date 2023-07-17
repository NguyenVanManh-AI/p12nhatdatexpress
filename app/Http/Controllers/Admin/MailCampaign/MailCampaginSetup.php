<?php

namespace App\Http\Controllers\Admin\MailCampaign;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailCampaginSetup extends Controller
{

    /**
     * @var string[]
     */
    private $ids;

    public function __construct()
    {
        $this->ids = ['N009', 'N010', 'N015'];
    }

    # Get config
    public function get_attr(){
        $notes = AdminConfig::whereIn('config_code', $this->ids)->get();
        return view('Admin.MailCampaign.Setting',compact("notes"));
    }

    # Lưu config
    public function post_attr(Request $request){
        foreach ($this->ids as $item){
            $config = AdminConfig::find($item);

            if (!$config) continue;

            // update note
            $config->update([
                'config_value' =>  $request->$item ?? ''
            ]);
            // $data =[
            //     $item=>$request->$item ?? ''
            // ];
            // Helper::create_admin_log(176,$data);
        }
        Toastr::success('Thành công');
        return back();
    }

}
