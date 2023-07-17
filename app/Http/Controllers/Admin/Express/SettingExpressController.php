<?php

namespace App\Http\Controllers\Admin\Express;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingExpressController extends Controller
{
    /**
     * Show setting
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(){
        $ids = ['N002', 'N003'];
        $notes = DB::table('admin_config')->whereIn('config_code', $ids)->get();
        return view('Admin.Express.SettingExpress', compact('notes'));
    }


    /**
     * Update setting
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request){
        $ids = ['N002', 'N003'];

        foreach ($ids as $item){
            $config = AdminConfig::firstWhere('config_code', $item);

            if (!$config) continue;

            $config->update([
                'config_value' =>  $request->$item ?? ''
            ]);
            // $data=[
            //     $item=>$request->$item ?? ''
            // ];
            // Helper::create_admin_log(97,$data);
        }

        Toastr::success('Thành công');
        return back();
    }
}
