<?php

namespace App\Http\Controllers\Admin\Rank;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Rank\AddRankRequest;
use App\Http\Requests\Admin\Rank\UpdateRankRequest;
use App\Models\AdminConfig;
use App\Models\User;
use App\Models\User\UserDetail;
use App\Models\User\UserLevel;

class RankController extends Controller
{

    //thêm viền thủ công
    public function add_border(){
        $list_users = User::orderBy('id','desc')->get();

        return view('Admin/Rank/AddBorder',['list_users'=>$list_users]);
    }

    public function post_add_border(Request $request){
        $validate = $request->validate([
            'list_users' => 'required|min:1|max:255',
            'image_url'=>'required|min:1|max:255',
        ]);

        for ($i = 0; $i < count($request->list_users); $i++) {
            $detail = UserDetail::firstWhere(['user_id' => $request->list_users[$i]]);
            if (!$detail) continue;

            $detail->update([
                'border' => $request->image_url
            ]);

            // Helper::create_admin_log(120,$data);
        }

        Toastr::success('Thêm viền thành công');
        return back();
    }

    //sửa cấp bậc
    public function edit($id){
        $item = UserLevel::findOrFail($id);

        return view('Admin/Rank/EditRank',['item'=>$item]);
    }

    public function post_edit(UpdateRankRequest $request,$id){
        $userLevel = UserLevel::findOrFail($id);

        $userLevel->update(
            [
                'level_name' => $request->level_name,
                'percent_special'=>$request->percent_special,
                'classified_min_quantity'=>$request->classified_min_quantity,
                'classified_max_quantity'=>$request->classified_max_quantity,
                'deposit_min_amount'=>$request->deposit_min_amount,
                'deposit_max_amount'=>$request->deposit_max_amount,
                'image_url'=>$request->image_url,
                'updated_at'=>time(),
                'updated_by'=>Auth::guard('admin')->user()->id,
            ]
        );
            // $data = [
            //     'id'=>$id,
            //     'level_name' => $request->level_name,
            //     'percent_special'=>$request->percent_special,
            //     'classified_min_quantity'=>$request->classified_min_quantity,
            //     'classified_max_quantity'=>$request->classified_max_quantity,
            //     'deposit_min_amount'=>$request->deposit_min_amount,
            //     'deposit_max_amount'=>$request->deposit_max_amount,
            //     'image_url'=>$request->image_url,
            //     'updated_at'=>time(),
            //     'updated_by'=>Auth::guard('admin')->user()->id,
            // ];
            // Helper::create_admin_log(121,$data);

        Toastr::success('Sửa thành công');
        return redirect()->route('admin.rank.list');
    }

    //thêm cấp bậc
    public function add(){
        return view('Admin/Rank/AddRank');
    }

    public function post_add(AddRankRequest $request){
        UserLevel::create(
            [
                'level_name' => $request->level_name,
                'percent_special'=>$request->percent_special,
                'classified_min_quantity'=>$request->classified_min_quantity,
                'classified_max_quantity'=>$request->classified_max_quantity,
                'deposit_min_amount'=>$request->deposit_min_amount,
                'deposit_max_amount'=>$request->deposit_max_amount,
                'image_url'=>$request->image_url,
                'created_at'=>time(),
                'created_by'=>Auth::guard('admin')->user()->id,
                'updated_at'=>time(),
                'updated_by'=>Auth::guard('admin')->user()->id,
            ]
        );
        // Helper::create_admin_log(122,$data);

        Toastr::success('Thêm thành công');
        return redirect()->route('admin.rank.list');
    }

    //xóa 1 cấp bậc
    public function trash_item($id)
    {
        $userLevel = UserLevel::findOrFail($id);
        $hasStatuses = $userLevel->levelStatuses->count();

        if (!$hasStatuses) {
            $userLevel->delete();
            // Helper::create_admin_log(123,$data);

            Toastr::success('Chuyển vào thùng rác thành công');
        } else {
            Toastr::warning('Không thể xóa! Đã có tài khoản liên kết với cấp bậc này.');
        }

        return back();
    }

    //khôi phục 1 cấp bậc
    public function untrash_item($id)
    {
        $userLevel = UserLevel::onlyIsDeleted()->findOrFail($id);
        $userLevel->restore();
        // Helper::create_admin_log(124,$data);

        Toastr::success('Thành công');
        return back();
    }

    //xóa nhiều cấp bậc
    public function trash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $userLevel = UserLevel::find($item);
            if (!$userLevel || $userLevel->levelStatuses->count()) continue;

            $userLevel->delete();
            // Helper::create_admin_log(123,$data);
        }

       Toastr::success('Chuyển vào thùng rác thành công');
       return back();
   }
    //khôi phục nhiều cấp bậc
   public function untrash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $userLevel = UserLevel::onlyIsDeleted()->find($item);
            if (!$userLevel) continue;

            $userLevel->restore();
            // Helper::create_admin_log(124,$data);
        }

        Toastr::success('Thành công');
        return back();

    }

    // public function forceDeleteMultiple(Request $request)
    // {
    //     $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

    //     UserLevel::onlyIsDeleted()->find($ids)
    //         ->each(function($item) {
    //             $item->forceDelete();

    //             // should create log force delete
    //         });

    //     Toastr::success('Xóa thành công');
    //     return back();
    // }

    public function trash(Request $request)
    {
        $items = $request->items ?: 10;

        //phân quyền
        if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $listQuery = UserLevel::onlyIsDeleted()
                ->orderBy('user_level.id','desc')
                ->join('admin', 'user_level.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->select('user_level.id','user_level.created_by','user_level.image_url','user_level.level_name','user_level.percent_special','user_level.classified_min_quantity','user_level.classified_max_quantity','user_level.deposit_min_amount','user_level.deposit_max_amount','user_level.created_at');
            }else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $listQuery = UserLevel::onlyIsDeleted()
                ->where('user_level.created_by',$admin_id)
                ->orderBy('user_level.id','desc');
            }else { // all || check
                $listQuery = UserLevel::onlyIsDeleted()
                ->orderBy('user_level.id','desc');
            }

        $list = $listQuery->paginate($items);

        return view('Admin/Rank/TrashListRank', [
            'list'=>$list
        ]);
    }

    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)){
            $items = $request->items;
        }

        //phân quyền
        if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $listQuery = UserLevel::query()
                ->orderBy('user_level.id','desc')
                ->join('admin', 'user_level.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->select('user_level.id','user_level.created_by','user_level.image_url','user_level.level_name','user_level.percent_special','user_level.classified_min_quantity','user_level.classified_max_quantity','user_level.deposit_min_amount','user_level.deposit_max_amount','user_level.created_at');
            }else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $listQuery = UserLevel::query()
                ->where('user_level.created_by',$admin_id)
                ->orderBy('user_level.id','desc');
            }else { // all || check
                $listQuery = UserLevel::query()
                ->orderBy('user_level.id','desc');
            }
        //phân quyền


        if($request->keyword){
            $listQuery->where('user_level.level_name', 'like', '%' . $request->keyword. '%');
        }
        if($request->from_date){
            $listQuery->where('user_level.created_at','>',date(strtotime($request->from_date)));
        }
        if($request->to_date){
            $listQuery->where('user_level.created_at','<',date(strtotime($request->to_date)));
        }

        $list = $listQuery->paginate($items);

        $count_trash = UserLevel::onlyIsDeleted()->count();

        $guide = AdminConfig::firstWhere('config_code', 'N013');

        return view('Admin/Rank/ListRank',
            [
                'list'=>$list,
                'trash_num'=>$count_trash,
                'guide' => $guide
            ]);

    }

    // setup rank content
    public function set_up_rank(Request $request){
        AdminConfig::updateOrCreate([
            'config_code', 'N013'
        ], [
            'config_value' =>  $request->content ?? ''
        ]);

        Toastr::success('Thành công');
        return back();
    }
}

