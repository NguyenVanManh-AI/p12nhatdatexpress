<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SettingController extends Controller
{
     public function comment_setting()
    {
        $getProject = AdminConfig::where('config_code', 'C005')->first();
        $getClassified = AdminConfig::where('config_code', 'C006')->first();
        $getPost = AdminConfig::where('config_code', 'C007')->first();
        return view('Admin/Comment/CommentSetting',
            [
                'getProject' => $getProject,
                'getClassified' => $getClassified,
                'getPost' => $getPost,
            ]
        );
    }

    public function post_comment_setting(Request $request)
    {
        $validate = $request->validate([
            'ngattrangduan' => 'required|integer|between:1,255',
            'ngattrangtindang' => 'required|integer|between:1,255',
            'ngattrangbaiviet' => 'required|integer|between:1,255',
        ]);

        $projectConfig = AdminConfig::firstWhere('config_code', 'C005');
        if ($projectConfig) {
            $projectConfig->update([
                'config_value' => $request->ngattrangduan
            ]);
        }

        $classifiedConfig = AdminConfig::firstWhere('config_code', 'C006');
        if ($classifiedConfig) {
            $classifiedConfig->update([
                'config_value' => $request->ngattrangduan
            ]);
        }

        $userPostConfig = AdminConfig::firstWhere('config_code', 'C007');
        if ($userPostConfig) {
            $userPostConfig->update([
                'config_value' => $request->ngattrangduan
            ]);
        }

        // $data = [
        //     'C005'=>$request->ngattrangduan,
        //     'C006'=>$request->ngattrangtindang,
        //     'C007'=> $request->ngattrangbaiviet
        // ];
        // Helper::create_admin_log(86,$data);

        Toastr::success('Cập nhật thành công');
        return back();
    }

}

