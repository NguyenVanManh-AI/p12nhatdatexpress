<?php

namespace App\Http\Controllers\Admin\FocusNews;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SetupController extends Controller
{
    public function get_setup(){
        $image_express = DB ::table('admin_config')
        ->where('admin_config.config_code','=','C015')
        ->first();

        $url_express = DB ::table('admin_config')
            ->where('admin_config.config_code','=','C017')
            ->first();

        $gg = DB ::table('admin_config')
        ->where('admin_config.config_code','=','C016')
        ->first();

        return view('Admin.FocusNews.SetUp',compact('image_express','url_express', 'gg'));
    }
    public function update(Request $request){
        $ids = ['C015','C016'];

        if($request->hasFile('file')){
            $c014 = DB::table('admin_config')->where('config_code','C015')->first();
            if($c014!=null && $c014->config_value !=null && file_exists(public_path($c014->config_value))){
                unlink(public_path($c014->config_value));
            }
            $name = time().'-'.Str::random(5).'.'. $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('system/config'),$name);
            $ten = 'system/config/'.$name;
            $c014 = DB::table('admin_config')->where('config_code','C015')->update([
               'config_value'=>$ten
            ]);
            $data['C015'] = $ten;
        }
        #store c015

            $c015 = DB::table('admin_config')->where('config_code','C016')->update([
            'config_value'=>$request->C016
            ]);
        $data['C016']=$request->C016??'';
          $data['C017']=$request->C017??'';
        Helper::create_admin_log(105,$data);

        $c015 = DB::table('admin_config')->where('config_code','C016')->update([
        'config_value'=>$request->C016 ?? ''
        ]);

        #store c017
        $c015 = DB::table('admin_config')->where('config_code','C017')->update([
            'config_value'=>$request->C017 ?? ''
        ]);

        Toastr::success('Thành công');
        return back();
    }

}
