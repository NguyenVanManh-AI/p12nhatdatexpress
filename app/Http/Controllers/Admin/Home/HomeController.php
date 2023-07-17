<?php
namespace App\Http\Controllers\Admin\Home;
use App\CPU\HelperImage;
use App\Http\Controllers\Controller;
use App\Models\HomeConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;



class HomeController extends Controller
{

    public function addhome()
    {
        $home_config = HomeConfig::first();
        return view('Admin.Home.Index', compact('home_config'));
    }

    public function posthome(Request $request)
    {
        $validate = $request->validate([
            'header_text_block' => 'required|min:10|max:255',
            'desktop_header_image' => 'image|max:5120',
            'mobile_header_image' => 'image|max:5120',
            'popup_image' => 'image|max:5120',
            'num_classified' => 'required|integer|min:1|max:100',
            'num_most_viewed' => 'required|integer|min:1|max:100',
            'popup_time' => 'required',
            'popup_link' => 'nullable|url|min:1|max:254',
        ],
            [
                'header_text_block.required' => "Vui lòng nhập từ 10-255 từ",
                'header_text_block.min' => "Vui lòng nhập tiêu đề lớn 10 từ",
                'header_text_block.max' => "Vui lòng nhập tiêu đề nhỏ hơn 255 từ",
                'desktop_header_image.required' => "Hình ảnh không được để trống",
                'desktop_header_image.image' => "Định dạng ảnh không hợp lệ",
                'desktop_header_image.max' => "Ảnh quá dung lượng kích cỡ",
                'mobile_header_image.required' => "Hình ảnh không được để trống",
                'mobile_header_image.image' => "Định dạng ảnh không hợp lệ",
                'mobile_header_image.max' => "Ảnh quá dung lượng kích cỡ",
                'popup_image.required' => "Hình ảnh không được để trống",
                'popup_image.image' => "Định dạng ảnh không hợp lệ",
                'popup_image.max' => "Ảnh quá dung lượng kích cỡ",
                'num_classified.required' => "Vui lòng chọn số tin ",
                'num_classified.integer' => "Chỉ được nhập số nguyên",
                'num_classified.min' => "Vui lòng nhập ký tự số nguyên từ 1 đến 100",
                'num_classified.max' => "Vui lòng nhập ký tự số nguyên từ 1 đến 100",
                'num_most_viewed.required' => "Vui lòng chọn số hiển thị",
                'num_most_viewed.integer' => "Chỉ được nhập số nguyên",
                'num_most_viewed.min' => "Vui lòng nhập ký tự số nguyên từ 1 đến 100",
                'num_most_viewed.max' => "Vui lòng nhập ký tự số nguyên từ 1 đến 100",
                'popup_time.required' => "Vui lòng chọn thời gian lặp lại",

                'popup_link.required' => "Vui lòng nhập từ 10-255 từ",
                'popup_link.min' => "Vui lòng nhập đường dẫn lớn 1 ký tự",
                'popup_link.max' => "Vui lòng nhập đường dẫn nhỏ hơn 255 ký tự",
                'popup_link.url' => "Vui lòng nhập đường dẫn đúng định dạng đường dẫn liên kết",
            ]);
        //thời gian lặp lại
//        <option value="0">1 ngày</option>
//                    <option value="1">1 tuần</option>
//                    <option value="2">10 ngày</option>
//                    <option value="3">1 tháng</option>

        switch($request->popup_time){
            case(0):
                $popup_time_expire = Carbon::now()->addDay(1);
                break;
            case(1):
                $popup_time_expire = Carbon::now()->addDay(7);
                break;
            case(2):
                $popup_time_expire = Carbon::now()->addDay(10);
                break;
            case(3):
                $popup_time_expire = Carbon::now()->addMonth(1);
                break;
        }

        $data = [
            'header_text_block' => $request->header_text_block,
            'num_most_viewed' => $request->num_most_viewed,
            'num_classified' => $request->num_classified,
            'popup_time' => $request->popup_time,
            'popup_link' => $request->popup_link,
            'popup_time_expire' => $popup_time_expire,
        ];

        if ($request->has('popup_status')) {
            $data['popup_status'] = 1;
        } else $data['popup_status'] = 0;

        $home_config = HomeConfig::first();
        if ($home_config == null) {
            $data['created_by'] = Auth::guard('admin')->user()->id;
            $data['created_at'] = time();
            //xử lý ảnh
            if ($request->hasFile('desktop_header_image')) {
                $data['desktop_header_image'] = HelperImage::saveImage('system/img/home-config', $request->file('desktop_header_image'));
            }
            if ($request->hasFile('mobile_header_image')) {
                $data['mobile_header_image'] = HelperImage::saveImage('system/img/home-config', $request->file('mobile_header_image'));
            }
            if ($request->hasFile('popup_image')) {
                $data['popup_image'] = HelperImage::saveImage('system/img/home-config', $request->file('popup_image'));
            }
            HomeConfig::insert($data);
            Toastr::success('Thành công');
            return redirect(route('admin.home.saved'));
        }
        else {

            $data['updated_by'] = Auth::guard('admin')->user()->id;
            $data['updated_at'] = time();
            if ($request->hasFile('desktop_header_image')) {
                $data['desktop_header_image'] = HelperImage::updateImage('system/img/home-config', $request->file('desktop_header_image'), $home_config->desktop_header_image);
            }
            if ($request->hasFile('mobile_header_image')) {
                $data['mobile_header_image'] = HelperImage::updateImage('system/img/home-config', $request->file('mobile_header_image'), $home_config->mobile_header_image);
            }
            if ($request->hasFile('popup_image')) {
                $data['popup_image'] = HelperImage::updateImage('system/img/home-config', $request->file('popup_image'), $home_config->popup_image);
            }
            HomeConfig::where('id', $home_config->id)->update($data);

            # Note log
            // Helper::create_admin_log(2, $data);

            Toastr::success('Thành công');
            return redirect(route('admin.home.saved'));
        }
    }

    public function delete_image($type ,$image, $id){

            $config = HomeConfig::findOrFail($id);
            $collect = collect($config)->toArray();

            if($collect[$type] == $image ){
                if(file_exists(public_path('system/img/home-config/'.$image))){
                    # Note log
                    // Helper::create_admin_log(3, ['image'=>'system/img/home-config/'.$image]);
                    unlink(public_path('system/img/home-config/'.$image));
                    $config->update([
                        $type=>null
                    ]);

                    Toastr::success("Xóa thành công");
                    return redirect(route('admin.home.saved'));
                }
            }

        Toastr::warning("không tồn tại ảnh");
        return back();
    }
}

