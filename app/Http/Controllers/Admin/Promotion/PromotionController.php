<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\CPU\HelperImage;
use App\Models\Promotion;
use App\Models\PromotionNew;
use App\Models\User;
use App\Models\User\UserVoucher;

class PromotionController extends Controller
{

        public function post_edit_news_promotion(Request $request, $id){

             $validate = $request->validate([
                'news_title' => 'required|max:255',
                'news_description' => 'required|max:255',
                'promotion_id' => 'integer',
                'news_content' => 'required|max:10000',
                'image'=>'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:2048',
            ], [
                'news_title.required' => '*Vui lòng nhập số lượng',
                'news_title.max' => '*Tiêu đề tối đa 255 ký tự',
                'news_description.required' => '*Vui lòng nhập mô tả',
                'news_description.max' => '*Mô tả tối đa 255 ký tự',
                'promotion_type.boolean' => '*Loại mã khuyến mãi là không xác định được',
                'promotion_id.required' => '*Vui lòng chọn mã',
                'promotion_id.integer' => '*Mã là một số nguyên',
                'news_content.required' => '*Vui lòng viết nội dung',
                'news_content.max' => '*Mô tả tối đa 1000 ký tự',
                'image.mimes'=>'Hình ảnh phải là jpeg,jpg,png,bmp,gif,svg',
                'image.max'=>'Tối đa 2MB',
            ]);

            $promotion = PromotionNew::findOrFail($id);
            $promotion_id = $request->promotion_id;

            $data = [
                'promotion_id' => $promotion_id,
                'news_title' => $request->news_title,
                'news_description' => $request->news_description,
                'news_content' => $request->news_content,
                'created_at' => time(),
                'created_by' => Auth::guard('admin')->user()->id,
                'updated_at' => time(),
                'updated_by' => Auth::guard('admin')->user()->id,
            ];

            if($request->hasFile('image')){
                $image = HelperImage::saveImage('system/images/post_promotion', $request->file('image'));
                $data['image'] = $image;
            }

            $promotion->update($data);
            // Helper::create_admin_log(169,$data);

            Toastr::success('Sửa thành công');
            return redirect()->route('admin.promotion.list-news-promotion');
        }

        public function edit_news_promotion($id){
         $list_code_0 = Promotion::query()
         ->where('is_private',1)
         ->where('date_from','<',time())
         ->where('date_to','>',time())
         ->orderBy('id','desc')->get();
         $promotion = PromotionNew::query()
         ->leftJoin('promotion','promotion.id','promotion_news.promotion_id')
         ->where('promotion_news.id','=',$id)
         ->select('promotion_news.id','promotion_news.news_title','promotion_news.news_content','promotion_news.news_description','promotion_news.image','promotion_news.created_by','promotion_news.promotion_id')
         ->first();
         return view('Admin.Promotion.EditNewsPromotion', ['promotion'=>$promotion,'list_code_0'=>$list_code_0]);

        }

        public function edit_promotion($id){
            $promotion = Promotion::where('id',$id)->first();
            return view('Admin.Promotion.EditPromotion', ['promotion'=>$promotion]);
        }
        public function post_edit_promotion(Request $request,$id){
            $validate = $request->validate([
                'promotion_type' => 'required|boolean',
                'num_use' => 'required|integer|between:1,1000',
                'value' => 'required|integer|between:1,100',
            ], [
                'promotion_type.required' => '*Vui lòng chọn loại khuyến mãi',
                'promotion_type.boolean' => '*Loại mã khuyến mãi là không xác định được',
                'num_use.required' => '*Vui lòng nhập số lần được sử dụng',
                'num_use.integer' => '*Số lần là một số nguyên',
                'num_use.between:1,10000' => '*Số lần nằm trong khoảng từ 1 đến 10000',
                'value.required' => '*Vui lòng nhập phần trăm muốn giảm',
                'value.integer' => '*Phầm trăm là số nguyên',
                'value.between:1,100' => '*Nằm trong khoảng từ 1 đến 100 phần trăm',
            ]);

            $promotion = Promotion::findOrFail($id);

            $date_from=$request->date_from;
            $date_to=$request->date_to;
            if($request->date_from == null){
                $date_from=Carbon::now('Asia/Ho_Chi_Minh');
            }
            if($request->date_to == null){
                $date_to=Carbon::now('Asia/Ho_Chi_Minh')->addYears(10);
            }

            // improve from old should check
            $data = [
                'promotion_type'=>$request->promotion_type,
                'value'=>$request->value,
                'num_use'=>$request->num_use,
                'date_from'=>date(strtotime($date_from)),
                'date_to'=>date(strtotime($date_to)),
                'updated_at'=>time(),
                'updated_by'=>Auth::guard('admin')->user()->id
            ];

            if($request->is_all == 'on'){
                $data['is_all'] = 1;
                $data['user_id_use'] = 0;
                $data['is_private'] = 0;
            }

            $promotion->update($data);
            // Helper::create_admin_log(173,$data);

            Toastr::success('Sửa thành công');
            return redirect()->route('admin.promotion.list-promotion');
        }

        public function post_add_news_promotion(Request $request){
         $validate = $request->validate([
            'news_title' => 'required|max:255',
            'news_description' => 'required|max:255',

            'news_content' => 'required|max:10000',
            'image'=>'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:2048',
        ], [
            'news_title.required' => '*Vui lòng nhập số lượng',
            'news_title.max' => '*Tiêu đề tối đa 255 ký tự',
            'news_description.required' => '*Vui lòng nhập mô tả',
            'news_description.max' => '*Mô tả tối đa 255 ký tự',
            'promotion_type.boolean' => '*Loại mã khuyến mãi là không xác định được',
            'promotion_id.integer' => '*Mã là một số nguyên',
            'news_content.required' => '*Vui lòng viết nội dung',
            'news_content.max' => '*Mô tả tối đa 1000 ký tự',
            'image.mimes'=>'Hình ảnh phải là jpeg,jpg,png,bmp,gif,svg',
            'image.max'=>'Tối đa 2MB',
        ]);
            $promotion_id = $request->promotion_id;

            $image="";
            if($request->hasFile('image')){
                $image = HelperImage::saveImage('system/images/post_promotion', $request->file('image'));
            }

            PromotionNew::create(
                [
                    'promotion_id' => $promotion_id,
                    'news_title'=>$request->news_title,
                    'news_description'=>$request->news_description,
                    'news_content'=>$request->news_content,

                    'image'=>$image,
                    'created_at'=>time(),
                    'created_by'=>Auth::guard('admin')->user()->id
                ]
            );
            // $data =  [
            //     'promotion_id' => $promotion_id,
            //     'news_title'=>$request->news_title,
            //     'news_description'=>$request->news_description,
            //     'news_content'=>$request->news_content,

            //     'image'=>$image,
            //     'created_at'=>time(),
            //     'created_by'=>Auth::guard('admin')->user()->id
            // ];
            // Helper::create_admin_log(168,$data);

            Toastr::success('Thêm thành công');
            return redirect()->route('admin.promotion.list-news-promotion');
        }

        public function add_news_promotion(){
            $list_code_0 = Promotion::query()
            ->where('is_private',1)
            ->where('date_from','<',time())
            ->where('date_to','>',time())
            ->orderBy('id','desc')->get();

            return view('Admin/Promotion/AddNewsPromotion',['list_code_0'=>$list_code_0]);
        }

        public function news_trash(Request $request){
            $items = $request->items ?: 10;

                if ($request->request_list_scope == 2) { // group
                    $admin_role_id = Auth::guard('admin')->user()->rol_id;
                    $list_promotion_news = PromotionNew::onlyIsDeleted()
                    ->leftJoin('admin', 'promotion_news.created_by', '=', 'admin.id')
                    ->leftJoin('promotion', 'promotion_news.promotion_id', '=', 'promotion.id')
                    ->where('admin.rol_id', $admin_role_id)
                    ->orderBy('promotion_news.id', 'DESC')
                    ->select('promotion_news.id','promotion_news.promotion_id','promotion_news.image','promotion_news.news_title','promotion_news.created_at','promotion_news.created_by','admin.admin_fullname','promotion.promotion_code','promotion.used','promotion.num_use','promotion.promotion_type','promotion.value','promotion.date_from','promotion.date_to')
                    ->paginate($items);
                }
                else if ($request->request_list_scope == 3) { //self
                    $admin_id = Auth::guard('admin')->user()->id;
                    $list_promotion_news = PromotionNew::onlyIsDeleted()
                    ->leftJoin('admin', 'promotion_news.created_by', '=', 'admin.id')
                    ->leftJoin('promotion', 'promotion_news.promotion_id', '=', 'promotion.id')
                    ->where(['promotion_news.created_by' => $admin_id])
                    ->orderBy('promotion_news.id', 'DESC')
                    ->select('promotion_news.id','promotion_news.image','promotion_news.promotion_id','promotion_news.news_title','promotion_news.created_at','promotion_news.created_by','admin.admin_fullname','promotion.promotion_code','promotion.used','promotion.num_use','promotion.promotion_type','promotion.value','promotion.date_from','promotion.date_to')
                    ->paginate($items);
                }
                else { // all || check
                    $list_promotion_news = PromotionNew::onlyIsDeleted()
                    ->leftJoin('admin', 'promotion_news.created_by', '=', 'admin.id')
                    ->leftJoin('promotion', 'promotion_news.promotion_id', '=', 'promotion.id')
                    ->orderBy('promotion_news.id', 'DESC')
                    ->select('promotion_news.id','promotion_news.promotion_id','promotion_news.image','promotion_news.news_title','promotion_news.created_at','promotion_news.created_by','admin.admin_fullname','promotion.promotion_code','promotion.used','promotion.num_use','promotion.promotion_type','promotion.value','promotion.date_from','promotion.date_to')
                    ->paginate($items);
                }

                return view('Admin/Promotion/TrashNewsPromotion', compact('list_promotion_news'));
            }
   #xóa 1 mã
     public function news_trash_item($id)
        {
            $promotionNew = PromotionNew::findOrFail($id);
            $promotionNew->delete();
            // Helper::create_admin_log(170,['id'=>$id,'is_deleted'=>1]);

            return back()->with('status', 'Chuyển vào thùng rác thành công');
        }

        public function newsForceDeleteMultiple(Request $request)
        {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

            PromotionNew::onlyIsDeleted()
                ->find($ids)
                ->each(function($promotion) {
                    $promotion->forceDelete();

                    // should create log force delete
                });

            Toastr::success('Xóa thành công');
            return back();
        }

        //khôi phục 1 mã
        public function news_untrash_item($id)
        {
            $promotionNew = PromotionNew::onlyIsDeleted()->findOrFail($id);
            $promotionNew->restore();
            // Helper::create_admin_log(171,['id'=>$id,'is_deleted'=>0]);

            Toastr::success('Thành công');
            return back();
        }
        //xóa nhiều mã
        public function news_trash_list(Request $request)
        {
            // dd($request->check);
            if ($request->select_item == null) {
                Toastr::warning("Vui lòng chọn");
                return back();
            }

            foreach ($request->select_item as $item) {
                $promotionNew = PromotionNew::find($item);
                if (!$promotionNew) continue;

                $promotionNew->delete();
                // Helper::create_admin_log(170,['id'=>$item,'is_deleted'=>1]);
            }

            Toastr::success('Thành công');
            return back();

        }
        //khôi phục nhiều mã
        public function news_untrash_list(Request $request)
        {
            // dd($request->check);
            if ($request->select_item == null) {
                Toastr::warning("Vui lòng chọn");
                return back();
            }

            foreach ($request->select_item as $item) {
                $promotionNew = PromotionNew::onlyIsDeleted()->find($item);
                if (!$promotionNew) continue;

                $promotionNew->delete();
                // Helper::create_admin_log(171,['id'=>$item,'is_deleted'=>0]);
            }

            Toastr::success('Thành công');
            return back();
        }

        public function list_news_promotion(Request $request)
        {

            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $items = 10;
            if ($request->has('items')) {
                $items = $request->items;
            }
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $list_news_promotion = PromotionNew::query()
                ->leftJoin('admin', 'promotion_news.created_by', '=', 'admin.id')
                ->leftJoin('promotion', 'promotion_news.promotion_id', '=', 'promotion.id')
                ->where('admin.rol_id', $admin_role_id)
                ->orderBy('promotion_news.id', 'DESC')
                ->select('promotion_news.id','promotion_news.promotion_id','promotion_news.image','promotion_news.news_title','promotion_news.created_at','promotion_news.created_by','admin.admin_fullname','promotion.promotion_code','promotion.used','promotion.num_use','promotion.promotion_type','promotion.value','promotion.date_from','promotion.date_to')
                ->paginate($items);
            }
            else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $list_news_promotion = PromotionNew::query()
                ->leftJoin('admin', 'promotion_news.created_by', '=', 'admin.id')
                ->leftJoin('promotion', 'promotion_news.promotion_id', '=', 'promotion.id')
                ->where(['promotion_news.created_by' => $admin_id])
                ->orderBy('promotion_news.id', 'DESC')
                ->select('promotion_news.id','promotion_news.promotion_id','promotion_news.image','promotion_news.news_title','promotion_news.created_at','promotion_news.created_by','admin.admin_fullname','promotion.promotion_code','promotion.used','promotion.num_use','promotion.promotion_type','promotion.value','promotion.date_from','promotion.date_to')
                ->paginate($items);
            }
            else { // all || check
                $list_news_promotion = PromotionNew::query()
                ->leftJoin('admin', 'promotion_news.created_by', '=', 'admin.id')
                ->leftJoin('promotion', 'promotion_news.promotion_id', '=', 'promotion.id')
                ->orderBy('promotion_news.id', 'DESC')
                ->select('promotion_news.id','promotion_news.promotion_id','promotion_news.image','promotion_news.news_title','promotion_news.created_at','promotion_news.created_by','admin.admin_fullname','promotion.promotion_code','promotion.used','promotion.num_use','promotion.promotion_type','promotion.value','promotion.date_from','promotion.date_to')
                ->paginate($items);
            }

            $count_trash = PromotionNew::onlyIsDeleted()->count();

            return view('Admin.Promotion.PromotionNews',
                [
                    'list_news_promotion'=>$list_news_promotion,
                    'date_from' => $date_from,
                    'date_to' =>$date_to,
                    'count_trash'=>$count_trash
                ]);
        }




        # Thêm mã khuyến mãi
        public function post_add_promotion(Request $request){
            $validate = $request->validate([
                'quanlity_code' => 'required|integer|between:1,200',
                'promotion_type' => 'required|boolean',
                'num_use' => 'required|integer|between:1,10000',
                // 'value' => 'required|integer|between:1,100',
            ], [
                'quanlity_code.required' => '*Vui lòng nhập số lượng',
                'quanlity_code.integer' => '*Số lượng là một số nguyên',
                'quanlity_code.between:1,200' => '*Số lượng nằm trong khoảng từ 1 đến 200',
                'promotion_type.required' => '*Vui lòng chọn loại khuyến mãi',
                'promotion_type.boolean' => '*Loại mã khuyến mãi là không xác định được',
                'num_use.required' => '*Vui lòng nhập số lần được sử dụng',
                'num_use.integer' => '*Số lần là một số nguyên',
                'num_use.between:1,10000' => '*Số lần nằm trong khoảng từ 1 đến 10000',
                'value.required' => '*Vui lòng nhập phần trăm muốn giảm',
                'value.integer' => '*Phầm trăm là số nguyên',
                'value.between:1,100' => '*Nằm trong khoảng từ 1 đến 100 phần trăm',
            ]);

            $date_from=$request->date_from;
            $date_to=$request->date_to;

            if($request->radio_button == null && $request->radio_button == null && $request->list_users == null){
                $request->radio_button='is_all';
            }

            if($request->date_from == null){
                $date_from=Carbon::now('Asia/Ho_Chi_Minh');
            }
            if($request->date_to == null){
                $date_to=Carbon::now('Asia/Ho_Chi_Minh')->addYears(10);
            }
            if($request->radio_button == "is_all" && $request->radio_button != "is_private" && $request->list_users == null){ //Mã khuyến mãi sử dụng cho tất cả mọi người
                for ($i = 1; $i <= $request->quanlity_code; $i++) {
                    Promotion::create(
                        [
                            'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                            'promotion_type'=>$request->promotion_type,
                            'value'=>$request->value,
                            'promotion_unit'=>0,
                            'num_use'=>$request->num_use,
                            'used'=>0,
                            'is_all'=>1,
                            'date_from'=>date(strtotime($date_from)),
                            'date_to'=>date(strtotime($date_to)),
                            'created_at'=>time(),
                            'created_by'=>Auth::guard('admin')->user()->id
                        ]
                    );
                    // $data = [
                    //     'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                    //     'promotion_type'=>$request->promotion_type,
                    //     'value'=>$request->value,
                    //     'promotion_unit'=>0,
                    //     'num_use'=>$request->num_use,
                    //     'used'=>0,
                    //     'is_all'=>1,
                    //     'date_from'=>date(strtotime($date_from)),
                    //     'date_to'=>date(strtotime($date_to)),
                    //     'created_at'=>time(),
                    //     'created_by'=>Auth::guard('admin')->user()->id
                    // ];
                    // Helper::create_admin_log(172,$data);
                }
                Toastr::success('Thêm thành công');
                return redirect()->route('admin.promotion.list-promotion');

            }else if($request->radio_button == "is_private" && $request->radio_button != "is_all" && $request->list_users == null){ //mã khuyến mãi cho trang
                for ($i = 1; $i <= $request->quanlity_code; $i++) {
                    Promotion::create(
                        [
                            'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                            'promotion_type'=>$request->promotion_type,
                            'value'=>$request->value,
                            'promotion_unit'=>0,
                            'num_use'=>$request->num_use,
                            'used'=>0,
                            'user_get'=>0,
                            'is_private'=>1,
                            'date_from'=>date(strtotime($date_from)),
                            'date_to'=>date(strtotime($date_to)),
                            'created_at'=>time(),
                            'created_by'=>Auth::guard('admin')->user()->id
                        ]
                    );
                    // $data =[
                    //     'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                    //     'promotion_type'=>$request->promotion_type,
                    //     'value'=>$request->value,
                    //     'promotion_unit'=>0,
                    //     'num_use'=>$request->num_use,
                    //     'used'=>0,
                    //     'user_get'=>0,
                    //     'is_private'=>1,
                    //     'date_from'=>date(strtotime($date_from)),
                    //     'date_to'=>date(strtotime($date_to)),
                    //     'created_at'=>time(),
                    //     'created_by'=>Auth::guard('admin')->user()->id
                    // ];
                    // Helper::create_admin_log(172,$data);

                }

                Toastr::success('Thêm thành công');
                return redirect()->route('admin.promotion.list-promotion');
            }else if($request->list_users != null){//mã chỉ định người
                for ($i = 0; $i < count($request->list_users); $i++) {
                    $infoVoucher = Promotion::create(
                        [
                            'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                            'promotion_type'=>$request->promotion_type,
                            'value'=>$request->value,
                            'promotion_unit'=>0,
                            'num_use'=>$request->num_use,
                            'used'=>0,
                            'user_id_use'=>$request->list_users[$i],
                            'date_from'=>date(strtotime($date_from)),
                            'date_to'=>date(strtotime($date_to)),
                            'created_at'=>time(),
                            'created_by'=>Auth::guard('admin')->user()->id
                        ]
                    );
                    // $data =[
                    //     'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                    //     'promotion_type'=>$request->promotion_type,
                    //     'value'=>$request->value,
                    //     'promotion_unit'=>0,
                    //     'num_use'=>$request->num_use,
                    //     'used'=>0,
                    //     'user_id_use'=>$request->list_users[$i],
                    //     'date_from'=>date(strtotime($date_from)),
                    //     'date_to'=>date(strtotime($date_to)),
                    //     'created_at'=>time(),
                    //     'created_by'=>Auth::guard('admin')->user()->id
                    // ];
                    // Helper::create_admin_log(172,$data);

                    // $infoVoucher = Promotion::where('id',$insertVoucher)->first();

                    UserVoucher::create(
                        [
                            'voucher_code' => $infoVoucher->promotion_code,
                            'voucher_name'=>'Voucher',
                            'amount'=>$infoVoucher->num_use,
                            'amount_used'=>0,
                            'voucher_type'=>$infoVoucher->promotion_type,
                            'voucher_percent'=>$infoVoucher->value,
                            'receipt_date'=>$infoVoucher->created_at,
                            'start_date'=>time(),
                            'end_date'=>$infoVoucher->date_to,
                            'user_id'=>$request->list_users[$i],
                            'created_by'=>Auth::guard('admin')->user()->id
                        ]
                    );
                }

                Toastr::success('Thêm thành công');
                return redirect()->route('admin.promotion.list-promotion');

            }
        }
        public function add_promotion(){
          $list_users = User::select('id', 'username')->orderBy('id','desc')->get();

          return view('Admin/Promotion/AddPromotion',['list_users'=>$list_users]);
        }

        public function trash(Request $request)
        {
            $items = $request->items ?: 10;

            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;

                $list_promotion = Promotion::onlyIsDeleted()
                ->select('promotion.id','promotion.used','promotion.num_use','promotion.promotion_unit','promotion.is_all','promotion.is_private','promotion.created_at','promotion.date_from','promotion.date_to','promotion.user_id_use','promotion_type','promotion_code','value','promotion.created_by')
                ->join('admin', 'promotion.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->orderBy('promotion.id', 'DESC')
                ->paginate($items);

            }
            else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;

                $list_promotion = Promotion::onlyIsDeleted()
                ->where(['created_by' => $admin_id])
                ->orderBy('id', 'DESC')
                ->paginate($items);
            }
            else { // all || check
                $list_promotion = Promotion::onlyIsDeleted()
                ->orderBy('id', 'DESC')
                ->paginate($items);
            }

            return view('Admin.Promotion.TrashPromotion', [
                'list_promotion' => $list_promotion
            ]);
        }
        //xóa 1 mã
        public function trash_item($id)
        {
            $promotion = Promotion::findOrFail($id);
            $promotion->delete();
            // Helper::create_admin_log(174,[
            //     'id'=>$id,
            //     'is_deleted'=>1
            // ]);

            return back()->with('status', 'Chuyển vào thùng rác thành công');
        }
        //khôi phục 1 mã
        public function untrash_item($id)
        {
            $promotion = Promotion::onlyIsDeleted()->findOrFail($id);
            $promotion->restore();
            // Helper::create_admin_log(174,[
            //     'id'=>$id,
            //     'is_deleted'=>0
            // ]);

            Toastr::success('Thành công');
            return back();
        }
        //xóa nhiều mã
        public function trash_list(Request $request)
        {
            if ($request->select_item == null) {
                Toastr::warning("Vui lòng chọn");
                return back();
            }

            foreach ($request->select_item as $item) {
                $promotion = Promotion::find($item);
                if (!$promotion) continue;
                $promotion->delete();
                // Helper::create_admin_log(174,[
                //     'id'=>$item,
                //     'is_deleted'=>1
                // ]);
            }

            Toastr::success('Thành công');
            return back();
        }

        //khôi phục nhiều mã
        public function untrash_list(Request $request)
        {
            if ($request->select_item == null) {
                Toastr::warning("Vui lòng chọn");
                return back();
            }

            foreach ($request->select_item as $item) {
               $promotion = Promotion::onlyIsDeleted()->find($item);
                if (!$promotion) continue;
                $promotion->restore();
                // Helper::create_admin_log(174,[
                //     'id'=>$item,
                //     'is_deleted'=>0
                // ]);
            }

            Toastr::success('Thành công');
            return back();
        }

        public function list_promotion(Request $request)
        {
            $date_from = $request->date_from;
            $date_to = $request->date_to;

            $date_from_unix = date(strtotime($request->date_from));
            $date_to_unix = date(strtotime($request->date_to)+86400);

            $items = $request->items ?: 10;

            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                if(isset($request->date_from) && isset($request->date_to)){
                    $list_promotion = Promotion::query()
                    ->select('promotion.id','promotion.used','promotion.num_use','promotion.promotion_unit','promotion.is_all','promotion.is_private','promotion.created_at','promotion.date_from','promotion.date_to','promotion.user_id_use','promotion_type','promotion_code','value','promotion.created_by')
                    ->where('promotion.id','!=', 1)
                    ->where('promotion.created_at','>',$date_from_unix)
                    ->where('promotion.created_at','<',$date_to_unix)
                    ->join('admin', 'promotion.created_by', '=', 'admin.id')
                    ->where('admin.rol_id', $admin_role_id)
                    ->orderBy('promotion.id', 'DESC')
                    ->paginate($items);
                }else{
                    $list_promotion = Promotion::query()
                    ->select('promotion.id','promotion.used','promotion.num_use','promotion.promotion_unit','promotion.is_all','promotion.is_private','promotion.created_at','promotion.date_from','promotion.date_to','promotion.user_id_use','promotion_type','promotion_code','value','promotion.created_by')
                    ->where('promotion.id','!=', 1)
                    ->join('admin', 'promotion.created_by', '=', 'admin.id')
                    ->where('admin.rol_id', $admin_role_id)
                    ->orderBy('promotion.id', 'DESC')
                    ->paginate($items);
                }
            }
            else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;

                if(isset($request->date_from) && isset($request->date_to)){
                    $list_promotion = Promotion::query()
                    ->where('created_by', $admin_id)
                    ->where('promotion.id','!=', 1)
                    ->where('promotion.created_at','>',$date_from_unix)
                    ->where('promotion.created_at','<',$date_to_unix)
                    ->orderBy('id', 'DESC')
                    ->paginate($items);
                }else{
                    $list_promotion = Promotion::query()
                    ->where('created_by', $admin_id)
                    ->where('promotion.id','!=', 1)
                    ->orderBy('id', 'DESC')
                    ->paginate($items);
                }

            }
            else { // all || check
                if(isset($request->date_from) && isset($request->date_to)){
                    $list_promotion = Promotion::query()
                    ->orderBy('id', 'DESC')
                    ->where('promotion.created_at','>',$date_from_unix)
                    ->where('promotion.created_at','<',$date_to_unix)
                    ->where('promotion.id','!=', 1)
                    ->paginate($items);
                }else{
                    $list_promotion = Promotion::query()
                    ->where('promotion.id','!=', 1)
                    ->orderBy('id', 'DESC')
                    ->paginate($items);
                }
            }

            $count_trash = Promotion::onlyIsDeleted()->count();

            return view('Admin/Promotion/Promotion',
                [
                    'list_promotion'=>$list_promotion,
                    'date_from' => $date_from,
                    'date_to' =>$date_to,
                    'count_trash'=>$count_trash
                ]);
        }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Promotion::onlyIsDeleted()
            ->find($ids)
            ->each(function($promotion) {
                $promotion->forceDelete();

                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }
}

