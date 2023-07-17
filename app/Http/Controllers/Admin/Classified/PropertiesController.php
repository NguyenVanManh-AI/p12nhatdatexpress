<?php

namespace App\Http\Controllers\Admin\Classified;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PropertiesController extends Controller
{
    public function list()
    {
        return view('Admin.Classified.Properties.List', [
            'properties' => getCacheProperties()
        ]);
    }

    public function post_list(Request $request)
    {
        foreach ($request->item as $key => $item) {
                $property = Property::find($item);
                if (!$property) continue;

                $property->update([
                    'name' => $request->properties[$key], // should check this maybe error
                    'updated_by' => Auth::guard('admin')->user()->id,
                    'updated_at' => time()
                ]);

                // Helper::create_admin_log(46,[
                //     'name'=>$request->properties[$key],
                //     'updated_by'=>Auth::guard('admin')->user()->id,
                //     'updated_at'=>time()
                // ]);
        }
        // forget properties cache
        Cache::forget('properties');

        Toastr::success("Cập nhật thuộc tính thành công");
        return back();
    }
}
