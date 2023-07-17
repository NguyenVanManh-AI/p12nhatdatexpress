<?php

namespace App\Http\Controllers\Admin\PersonalPage;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use App\Models\User\UserLevel;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalSettingController extends Controller
{
    public function personal_setting()
    {
        $ids = ['C008', 'C009', 'C013'];
        $notes = AdminConfig::whereIn('config_code', $ids)->get();
        $param['level'] = UserLevel::query()
            ->get();

        return view('Admin/PersonalPage/Setting',
            compact('notes', 'param'));
    }

    public function post_personal_setting(Request $request)
    {
        $ids = ['C008', 'C009', 'C013'];

        foreach ($ids as $item) {
            $config = AdminConfig::find($item);

            if (!$config) continue;

            $config->update([
                'config_value' => $request->$item ?? ''
            ]);

            // $data[$item] = $request->$item ?? '';
        }

        // Helper::create_admin_log(194, $data);
        Toastr::success('Thành công');
        return back();

    }

}
