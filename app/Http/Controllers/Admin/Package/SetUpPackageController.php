<?php

namespace App\Http\Controllers\Admin\Package;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ClassifiedPackage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetUpPackageController extends Controller
{
    public function set_up(Request $request){

        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
        //check group
        if($request->request_list_scope == 2){
            $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
            $list_setup = ClassifiedPackage::query()
            ->join('admin','classified_package.created_by','=','admin.id')
            ->select('classified_package.id','classified_package.package_name','classified_package.price','classified_package.discount_price','classified_package.classified_nomal_amount','classified_package.vip_amount','classified_package.highlight_amount','classified_package.highlight_duration','classified_package.is_deleted','classified_package.created_by')
            ->where(['admin.rol_id'=>$admin_role_id]);

        }
        //check self
        else if($request->request_list_scope == 3){
            $admin_id = Auth::guard('admin')->user()->id; // 1. lấy id role
            $list_setup = ClassifiedPackage::query()
            ->select('classified_package.id','classified_package.package_name','classified_package.price','classified_package.discount_price','classified_package.classified_nomal_amount','classified_package.vip_amount','classified_package.highlight_amount','classified_package.highlight_duration','classified_package.is_deleted','classified_package.created_by')
            ->where(['classified_package.created_by'=>$admin_id]);
        }
        //check all
        else {
            // $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
            $list_setup = ClassifiedPackage::query()
            ->select('classified_package.id','classified_package.package_name','classified_package.price','classified_package.discount_price','classified_package.classified_nomal_amount','classified_package.vip_amount','classified_package.highlight_amount','classified_package.highlight_duration','classified_package.is_deleted','classified_package.created_by');
        }
        if(isset($request->package_type)&& $request->package_type!=""){
            $list_setup->where('package_type',$request->package_type);
        }
        // $list_setup = $list_setup->paginate($items);
        $list_setup = $list_setup->leftJoin('user_transaction', 'classified_package.id', '=', 'user_transaction.post_package_id')
        ->select('classified_package.id', 'classified_package.package_name', 'classified_package.price', 'classified_package.best_seller',
            'classified_package.discount_price', 'classified_package.classified_nomal_amount',
            'classified_package.vip_amount', 'classified_package.highlight_amount',
            'classified_package.highlight_duration', 'classified_package.is_deleted',
            'classified_package.created_by'
        )
        ->groupBy('classified_package.id')->paginate($items);

        $count_trash = ClassifiedPackage::onlyIsDeleted()->count();

        return view('Admin.Package.SetUpPackage',compact('list_setup','count_trash'));

    }
    public function trash( Request $request){
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        }
               //check group
               if($request->request_list_scope == 2){
                $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
                $trash_setup = ClassifiedPackage::query()
                ->join('admin','classified_package.created_by','=','admin.id')
                ->select('classified_package.id','classified_package.package_name','classified_package.price','classified_package.discount_price','classified_package.classified_nomal_amount','classified_package.vip_amount','classified_package.highlight_amount','classified_package.highlight_duration','classified_package.is_deleted','classified_package.created_by')
                ->where(['admin.rol_id'=>$admin_role_id]);

            }
            //check self
            else if($request->request_list_scope == 3){
                $admin_id = Auth::guard('admin')->user()->id; // 1. lấy id role
                $trash_setup = ClassifiedPackage::query()
                ->select('classified_package.id','classified_package.package_name','classified_package.price','classified_package.discount_price','classified_package.classified_nomal_amount','classified_package.vip_amount','classified_package.highlight_amount','classified_package.highlight_duration','classified_package.is_deleted','classified_package.created_by')
                ->where(['classified_package.created_by'=>$admin_id]);
            }
            //check all
            else {
                // $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
                $trash_setup = ClassifiedPackage::query()
                ->select('classified_package.id','classified_package.package_name','classified_package.price','classified_package.discount_price','classified_package.classified_nomal_amount','classified_package.vip_amount','classified_package.highlight_amount','classified_package.highlight_duration','classified_package.is_deleted','classified_package.created_by', 'duration_time');
            }
        $trash_setup = $trash_setup->onlyIsDeleted()->paginate($items);

        return view('Admin.Package.TrashSetUpPackage',compact('trash_setup'));
    }

    public function add(){

        return view('Admin.Package.AddSetup');
    }

    public function post_package( Request $request){
        $validate = $request->validate([
            'package_name' => 'required|unique:classified_package,package_name',
            'package_type'=> 'required',
            'price'=> 'required|integer',
            'duration_time'=>'required|integer',
            'discount_price'=> 'required|integer',
            'classified_nomal_amount' => 'required|integer',
            'vip_amount'=> 'required|integer',
            'highlight_amount'=> 'required|integer',
            'classified_per_day'=> 'required|integer',
            'vip_duration'=>'required|integer',
            'highlight_duration'=>'required|integer',
            'qr_image'=>'required|max:254',

        ], [
           'package_name.required'=>'*Vui lòng nhập tên gói tin ' ,
           'package_name.unique'=>'*Tên gói tin đã tồn tại ' ,
           'package_type.required'=>'*Vui lòng chọn loại gói tin',
           'price.required'=>'Vui lòng nhập giá',
           'price.integer'=>'Vui lòng nhập số nguyên',
           'duration_time.required'=>'Vui lòng nhập thời gian sử dụng',
           'duration_time.integer'=>'Vui lòng nhập số nguyên',
           'discount_price.required'=>'*Vui lòng nhập giá khuyến mãi',
           'discount_price.integer'=>'Vui lòng nhập số nguyên',
           'classified_nomal_amount.required'=>'*Vui lòng nhập số tin thường',
           'classified_nomal_amount.integer'=>'Vui lòng nhập số nguyên',
           'vip_amount.required'=>'*Vui lòng nhập số tin vip',
           'vip_amount.integer'=>'Vui lòng nhập số nguyên',
           'highlight_amount.required'=>'*Vui lòng nhập số tin nổi bật',
           'highlight_amount.integer'=>'Vui lòng nhập số nguyên',
           'classified_per_day.required'=>'*Vui lòng nhập số tin thường trong ngày',
           'classified_per_day.integer'=>'Vui lòng nhập số nguyên',
           'vip_duration.required'=>'*Vui lòng chọn thời gian vip',
           'vip_duration.integer'=>'Vui lòng nhập số nguyên',
           'highlight_duration.required'=>'*Vui lòng chọn thời gian nổi bật',
           'highlight_duration.integer'=>'Vui lòng nhập số nguyên',
           'qr_image.required'=>'Vui lòng chọn ảnh QR',
           'qr_image.max'=>'Đường dẫn ảnh không được dài hơn 254 kí tự',
        ]);

        $data = [
            'package_name' => $request->package_name,
            'package_type' => $request->package_type,
            'price' => $request->price,
            'duration_time'=>$request->duration_time,
            'discount_price' => $request->discount_price,
            'classified_nomal_amount' => $request->classified_nomal_amount,
            'vip_amount' => $request->vip_amount,
            'highlight_amount' => $request->highlight_amount,
            'classified_per_day' => $request->classified_per_day,
            'vip_duration' => $request->vip_duration,
            'highlight_duration' => $request->highlight_duration,
            'qr_image' => $request->qr_image,
            'created_by' => Auth::guard('admin')->user()->id
        ];
        ($request->has('cus_mana'))?$data['cus_mana']= 1:$data['cus_mana']= 0;
        ($request->has('data_static'))?$data['data_static']= 1:$data['data_static']= 0;

        $data['best_seller'] = $request->best_seller ? true : false;

        ClassifiedPackage::create($data);
        // Helper::create_admin_log(116,$data);

        Toastr::success('Thêm thành công');
        return redirect(route('admin.setup.list'));
    }

    public function edit_package( Request $request,$id){
        $package = ClassifiedPackage::findOrFail($id);

        return view('Admin.Package.EditSetup', compact('package'));
    }

        public function postpackage(Request $request,$id){
            // dd($request->all());
            $validate = $request->validate([
                'package_name' => 'required|unique:classified_package,package_name,'.$id,
                'package_type'=> 'required',
                'price'=> 'required|integer',
                'duration_time'=>'required|integer',
                'discount_price'=> 'required|integer',
                'classified_nomal_amount' => 'required|integer',
                'vip_amount'=> 'required|integer',
                'highlight_amount'=> 'required|integer',
                'classified_per_day'=> 'required|integer',
                'vip_duration'=>'required|integer',
                'highlight_duration'=>'required|integer',
                'qr_image'=>'required|max:254',
            ], [
               'package_name.required'=>'*Vui lòng nhập tên gói tin ' ,
               'package_name.unique'=>'*Tên gói tin đã tồn tại ' ,
               'package_type.required'=>'*Vui lòng chọn loại gói tin',
               'price.required'=>'Vui lòng nhập giá',
               'price.integer'=>'Vui lòng nhập số nguyên',
               'duration_time.required'=>'Vui lòng nhập thời gian sử dụng',
               'duration_time.integer'=>'Vui lòng nhập số nguyên',
               'discount_price.required'=>'*Vui lòng nhập giá khuyến mãi',
               'discount_price.integer'=>'Vui lòng nhập số nguyên',
               'classified_nomal_amount.required'=>'*Vui lòng nhập số tin thường',
               'classified_nomal_amount.integer'=>'Vui lòng nhập số nguyên',
               'vip_amount.required'=>'*Vui lòng nhập số tin vip',
               'vip_amount.integer'=>'Vui lòng nhập số nguyên',
               'highlight_amount.required'=>'*Vui lòng nhập số tin nổi bật',
               'highlight_amount.integer'=>'Vui lòng nhập số nguyên',
               'classified_per_day.required'=>'*Vui lòng nhập số tin thường trong ngày',
               'classified_per_day.integer'=>'Vui lòng nhập số nguyên',
               'vip_duration.required'=>'*Vui lòng chọn thời gian vip',
               'vip_duration.integer'=>'Vui lòng nhập số nguyên',
               'highlight_duration.required'=>'*Vui lòng chọn thời gian nổi bật',
               'highlight_duration.integer'=>'Vui lòng nhập số nguyên',
                'qr_image.required'=>'Vui lòng chọn ảnh QR',
                'qr_image.max'=>'Đường dẫn ảnh không được dài hơn 254 kí tự',
            ]);

        $package = ClassifiedPackage::findOrFail($id);

        $data = [
            'package_name' => $request->package_name,
            'package_type' => $request->package_type,
            'price' => $request->price,
            'duration_time' => $request->duration_time,
            'discount_price' => $request->discount_price,
            'classified_nomal_amount' => $request->classified_nomal_amount,
            'vip_amount' => $request->vip_amount,
            'highlight_amount' => $request->highlight_amount,
            'classified_per_day' => $request->classified_per_day,
            'vip_duration' => $request->vip_duration,
            'highlight_duration' => $request->highlight_duration,
            'qr_image' => $request->qr_image,
            'created_by' => Auth::guard('admin')->user()->id
        ];

        ($request->has('cus_mana'))?$data['cus_mana']= 1:$data['cus_mana']= 0;
        ($request->has('data_static'))?$data['data_static']= 1:$data['data_static']= 0;

        $data['best_seller'] = $request->best_seller ? true : false;

        $package->update($data);
        // $data['id'] = $id;
        // Helper::create_admin_log(117,$data);

        Toastr::success('Cập nhật thành công');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        ClassifiedPackage::query()
            ->find($ids)
            ->each(function($item) {
                $item->delete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        ClassifiedPackage::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->restore();
            });

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        ClassifiedPackage::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }
}
