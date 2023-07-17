<?php

namespace App\Http\Controllers\Admin\CategoryPageConfigController;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use App\Models\Group;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryPageConfigController extends Controller
{
    protected $var_array = array(2, 10, 18);

    //lấy thông tin cấu hình củ
    public function page_config()
    {
        $group = Group::whereIn('id', $this->var_array)->get();
        $admin_config = AdminConfig::get();

        return view('Admin.CategoryPageConfig.PageConfig', compact('admin_config', 'group'));
    }

    public function edit_page_config(Request $request)
    {
        $validate = $request->validate([
            'info_category' => 'required|max:5000',
            'info_location' => 'required|max:5000',
            'image_banner' => 'image',
            'image_banner_mobile' => 'image',
            'list_post' => 'required|integer|between:1,100',
        ], [
            'info_category.required' => '*Vui lòng văn bản',
            'info_category.max' => '*Tối đa 5000 ký tự',
            'info_location.required' => '*Vui lòng văn bản',
            'info_location.max' => '*Tối đa 5000 ký tự',
            'list_post.required' => '*Vui lòng nhập số tin hiển thị',
            'list_post.integer' => 'Vui lòng nhập số nguyên',
            'list_post.between' => 'Số tin trong khoảng từ 1 đến 100',
            'image_banner.image' => 'Chỉ tải lên được ảnh',
            'image_banner_mobile.image' => 'Chỉ tải lên được ảnh',
        ]);

        if ($request->has('group') && $request->group != null) {
            if ($request->hasFile('image_banner')) {
                $group = Group::find($request->group);
                if ($group != null && $group->image_banner != null && file_exists(public_path($group->image_banner))) {
                    unlink(public_path($group->image_banner));
                }
                $name = time() . '-' . Str::random(10) . '.' . $request->file('image_banner')->getClientOriginalExtension();
                $request->file('image_banner')->move(public_path('system/group/'), $name);

                $group->update([
                    'image_banner' => 'system/group/' . $name
                ]);
            }
        }
        if ($request->has('group_mobile') && $request->group != null) {
            if ($request->hasFile('image_banner_mobile')) {
                $group = Group::find($request->group_mobile);
                if ($group != null && $group->image_banner_mobile != null && file_exists(public_path($group->image_banner_mobile))) {
                    unlink(public_path($group->image_banner_mobile));
                }
                $name = time() . '-' . Str::random(10) . '.' . $request->file('image_banner_mobile')->getClientOriginalExtension();
                $request->file('image_banner_mobile')->move(public_path('system/group/'), $name);

                $group->update([
                    'image_banner_mobile' => 'system/group/' . $name
                ]);
            }
        }

        $listPost = AdminConfig::find(1);
        if ($listPost) {
            $listPost->update([
                'config_value' => $request->list_post
            ]);
        }

        $gr = Group::find(4);
        if ($gr) {
            $gr->update([
                'group_name' => 'Nhà riêng'
            ]);
        }

        $infoCategory = AdminConfig::find(2);
        if ($infoCategory) {
            $infoCategory->update([
                'config_value' => $request->info_category
            ]);
        }

        $infoLocation = AdminConfig::find(3);
        if ($infoLocation) {
            $infoLocation->update([
                'config_value' => $request->info_location
            ]);
        }

        # Note log
        // Helper::create_admin_log(4, [$request->info_category, $request->info_location, $request->list_post]);

        Toastr::success('Cập nhật thành công');
        return back();
    }
    /////------------------------------------------------------------------------OTHER METHOD------------------------------------------------------------------------//
    public function objectToArray($input): array
    {
        $new = [];
        foreach ($input as $item) {
            if (is_object($item) || is_array($item)) {
                $new[$item['name']] = $item['value'];
            }
        }
        return (array) $new;
    }
}
