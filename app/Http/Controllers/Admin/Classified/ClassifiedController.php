<?php

namespace App\Http\Controllers\Admin\Classified;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Classified\ClassifiedUpdateRequest;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedLocation;
use App\Models\Classified\ClassifiedParam;
use App\Models\Direction;
use App\Models\Group;
use App\Models\Project;
use App\Models\Property;
use App\Models\Province;
use App\Models\Unit;
use App\Services\GroupService;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassifiedController extends Controller
{
    private GroupService $groupService;

    public function __construct()
    {
        $this->groupService = new GroupService;
    }

    public function list(Request $request)
    {
        // lấy items dùng để phân trang
        $items = $request->items ?? 10;
        // kiểm tra có lọc theo chuyên mục ?
        if(isset($request->group_id) && $request->group_id != ""){
            $group_parent =$this->groupService->getChildren($request->group_id);
            $group = $this->groupService->prepareData($group_parent);
            $classified = new Collection([]);
            foreach ($group as $item){
                $classified_query = Classified::query();
                $classified_query = $classified_query
                    ->join('group', 'classified.group_id', '=', 'group.id')
                    ->leftJoin('group as group_parent', 'group.parent_id', '=', 'group_parent.id')
                    ->leftJoin('user','classified.user_id','=','user.id')
                    ->leftJoin('user_detail','classified.user_id','=','user_detail.id')
                    ->leftJoin('admin','classified.confirmed_by','=','admin.id')
                    ->select('classified.*', 'user.phone_number','admin.rol_id','admin.id as admin_id','group.group_name as group_child', 'group.id as id_group_child', 'group_parent.group_name as group_parent', 'group_parent.id as id_group_parent',
                    'user.email', 'user_detail.fullname', 'user.username')
                    ->where('group_id',$item->id);
                $classified_query = $classified_query->get();
                    $classified = $classified->merge($classified_query);
            }
        }
        // Nếu không lọc theo chuyên mục sẽ lấy tất cả các tin rao
        else{
            $classified_query = Classified::query()
                ->join('group', 'classified.group_id', '=', 'group.id')
                ->leftJoin('group as group_parent', 'group.parent_id', '=', 'group_parent.id')
                ->leftJoin('user','classified.user_id','=','user.id')
                ->leftJoin('user_detail','classified.user_id','=','user_detail.id')
                ->leftJoin('admin','classified.confirmed_by','=','admin.id')
                ->select('classified.*', 'user.phone_number','admin.rol_id','admin.id as admin_id','group.group_name as group_child', 'group.id as id_group_child', 'group_parent.group_name as group_parent', 'group_parent.id as id_group_parent',
                'user.email', 'user_detail.fullname', 'user.username');
            $classified = $classified_query->get();
        }

        //lọc theo chuyên mục - mô hình
        if(isset($request->group_child) && $request->group_child!=""){
                $classified_child=$classified->where('id_group_child',$request->group_child);
                $classified_parent = $classified->where('id_group_parent',$request->group_child);
                $classified = $classified_child->merge($classified_parent);
        }
        // lọc theo loại tin đăng
        if(isset($request->classified_type)&& $request->classified_type!=""){
            if($request->classified_type==1){
                $classified = $classified->where('is_vip',1);
                $classified = $classified->where('vip_end','>',time());
            }
            elseif($request->classified_type==2){
                $classified = $classified->where('is_hightlight',1);
                $classified = $classified->where('hightlight_end','>',time());
            }
            else{
                $classified = $classified->where('vip_end','<=',time());
                $classified = $classified->where('vip_end','=',null);
                $classified = $classified->where('hightlight_end','<=',time());
                $classified = $classified->where('hightlight_end','=',null);
            }
        }
        // lọc theo tình trạng
        if(isset($request->classified_status)&& $request->classified_status!=""){
            if ($request->classified_status != 3)
                $classified = $classified
                    ->where('expired_date', '>', time())
                    ->where('confirmed_status', $request->classified_status);
            else
                $classified = $classified->where('expired_date', '<', time());
        }
        // lọc theo mã tin

        if(isset($request->classified_id) && $request->classified_id!=""){
            $classified = $classified->where('classified_code',$request->classified_id);
        }
        //Lọc theo số điện thoại
        if(isset($request->keyword) && $request->keyword!=""){
            $classified = $classified->filter(function ($classified) use ($request){
                return
                    Str::contains(Str::lower($classified->classified_name), Str::lower($request->keyword));
                    // ||
                    // Str::contains(Str::lower($classified->fullname), Str::lower($request->keyword)) ||
                    // $classified->phone_number == $request->keyword ||
                    // $classified->email == $request->keyword;
            });
        }

        // lọc theo thời gian
        if(
            (isset($request->start_day) && $request->start_day!="") || (isset($request->end_day) && $request->end_day!="")
        ){

            if($request->start_day==""){
                $end_day =Carbon::parse($request->end_day)->addDay(1)->setTimezone('Asia/Ho_Chi_Minh');
                $classified = $classified->where('created_at','<',strtotime($end_day));
            }
            elseif((isset($request->end_day) && $request->end_day=="")){
                $start_day=Carbon::parse($request->start_day)->setTimezone('Asia/Ho_Chi_Minh');
                $classified = $classified->where('created_at','>',strtotime($start_day));
            }
            else{
                $start_day=Carbon::parse($request->start_day)->setTimezone('Asia/Ho_Chi_Minh');
                $end_day =Carbon::parse($request->end_day)->addDay(1)->setTimezone('Asia/Ho_Chi_Minh');
                $classified = $classified->where('created_at','<',strtotime($end_day));
                $classified = $classified->where('created_at','>',strtotime($start_day));
            }
        }
         // phân quyền danh sách
         // group
        if ($request->request_list_scope == 2) {
           $admin_role = Auth::guard('admin')->user()->rol_id;
            $classified = $classified->where('rol_id',$admin_role);
        }
        // self
        elseif($request->request_list_scope == 3){
            $admin_id = Auth::guard('admin')->user()->id;
            $classified = $classified->where('confirmed_by',$admin_id);
        }
        $classified = $classified->sortByDesc('created_at');
        $classified = CollectionHelper::paginate($classified,$items);
        $trash_count = Classified::onlyIsDeleted()->count();

        return view('Admin.Classified.ListClassified.ListClassified',compact('classified','trash_count'));
    }

    public function list_trash(Request $request)
    {

        // lấy items dùng để phân trang
        $items = 10;
        if(isset($_GET['items'])){
            $items = $_GET['items'];
        }
        // kiểm tra có lọc theo chuyên mục ?
        if(isset($request->group_id) && $request->group_id != ""){
            $group_parent =$this->groupService->getChildren($request->group_id);
            $group = $this->groupService->prepareData($group_parent);
            $classified = new Collection([]);
            foreach ($group as $item){
                $classified_query = Classified::onlyIsDeleted();
                $classified_query = $classified_query
                    ->join('group', 'classified.group_id', '=', 'group.id')
                    ->leftJoin('group as group_parent', 'group.parent_id', '=', 'group_parent.id')
                    ->leftJoin('user','classified.user_id','=','user.id')
                    ->leftJoin('user_detail','classified.user_id','=','user_detail.id')
                    ->leftJoin('admin','classified.confirmed_by','=','admin.id')
                    ->select('classified.*', 'user.phone_number','admin.rol_id','admin.id as admin_id','group.group_name as group_child', 'group.id as id_group_child', 'group_parent.group_name as group_parent', 'group_parent.id as id_group_parent',
                    'user.email', 'user_detail.fullname', 'user.username')
                    ->where('group_id',$item->id);
                $classified_query = $classified_query->get();
                $classified = $classified->merge($classified_query);
            }
        }
        // Nếu không lọc theo chuyên mục sẽ lấy tất cả các tin rao
        else{
            $classified_query = Classified::onlyIsDeleted();
            $classified_query = $classified_query
                ->join('group', 'classified.group_id', '=', 'group.id')
                ->leftJoin('group as group_parent', 'group.parent_id', '=', 'group_parent.id')
                ->leftJoin('user','classified.user_id','=','user.id')
                ->leftJoin('user_detail','classified.user_id','=','user_detail.id')
                ->leftJoin('admin','classified.confirmed_by','=','admin.id')
                ->select('classified.*', 'user.phone_number','admin.rol_id','admin.id as admin_id','group.group_name as group_child', 'group.id as id_group_child', 'group_parent.group_name as group_parent', 'group_parent.id as id_group_parent',
                'user.email', 'user_detail.fullname', 'user.username');
            $classified = $classified_query->get();
        }

        //lọc theo chuyên mục - mô hình
        if(isset($request->group_child) && $request->group_child!=""){
            $classified_child=$classified->where('id_group_child',$request->group_child);
            $classified_parent = $classified->where('id_group_parent',$request->group_child);
            $classified = $classified_child->merge($classified_parent);
        }
        // lọc theo loại tin đăng
        if(isset($request->classified_type)&& $request->classified_type!=""){
            if($request->classified_type==1){
                $classified = $classified->where('is_vip',1);
                $classified = $classified->where('vip_end','>',time());
            }
            elseif($request->classified_type==2){
                $classified = $classified->where('is_hightlight',1);
                $classified = $classified->where('hightlight_end','>',time());
            }
            else{
                $classified = $classified->where('vip_end','<=',time());
                $classified = $classified->where('vip_end','=',null);
                $classified = $classified->where('hightlight_end','<=',time());
                $classified = $classified->where('hightlight_end','=',null);
            }
        }
        // lọc theo tình trạng
        if(isset($request->classified_status)&& $request->classified_status!=""){
//            if($request->classified_status)
        }
        // lọc theo mã tin

        if(isset($request->classified_id) && $request->classified_id!=""){
            $classified = $classified->where('id',$request->classified_id);
        }
        //Lọc theo số điện thoại
        if(isset($request->keyword) && $request->keyword!=""){
            $classified = $classified->filter(function ($classified) use ($request){
                return
                    Str::contains(Str::lower($classified->classified_name), Str::lower($request->keyword));
                    // ||
                    // Str::contains(Str::lower($classified->fullname), Str::lower($request->keyword)) ||
                    // $classified->phone_number == $request->keyword ||
                    // $classified->email == $request->keyword;
            });
        }

        // lọc theo thời gian
        if(
            (isset($request->start_day) && $request->start_day!="") || (isset($request->end_day) && $request->end_day!="")
        ){

            if($request->start_day==""){
                $end_day =Carbon::parse($request->end_day)->addDay(1)->setTimezone('Asia/Ho_Chi_Minh');
                $classified = $classified->where('created_at','<',strtotime($end_day));
            }
            elseif((isset($request->end_day) && $request->end_day=="")){
                $start_day=Carbon::parse($request->start_day)->setTimezone('Asia/Ho_Chi_Minh');
                $classified = $classified->where('created_at','>',strtotime($start_day));
            }
            else{
                $start_day=Carbon::parse($request->start_day)->setTimezone('Asia/Ho_Chi_Minh');
                $end_day =Carbon::parse($request->end_day)->addDay(1)->setTimezone('Asia/Ho_Chi_Minh');
                $classified = $classified->where('created_at','<',strtotime($end_day));
                $classified = $classified->where('created_at','>',strtotime($start_day));
            }
        }
        // phân quyền danh sách
        // group
        if ($request->request_list_scope == 2) {
            $admin_role = Auth::guard('admin')->user()->rol_id;
            $classified = $classified->where('rol_id',$admin_role);
        }
        // self
        elseif($request->request_list_scope == 3){
            $admin_id = Auth::guard('admin')->user()->id;
            $classified = $classified->where('confirmed_by',$admin_id);
        }
        $classified->sortBy('created_at');

        $classified = CollectionHelper::paginate($classified,$items);
        return view('Admin.Classified.ListClassified.TrashClassified',compact('classified'));
    }
    public function refresh($id){
        $classified = Classified::find($id);
        if ($classified == null){
            Toastr::error("Không tồn tại tin đăng");
            return back();
        }
        $time =Carbon::parse($classified->expired_date)->setTimezone('Asia/Ho_Chi_Minh')->addMonth(6);
        $classified->update([
            'expired_date' => strtotime($time)
        ]);
        // Helper::create_admin_log(25,[
        //     'id'=>$id,
        //     'expired_date'=>strtotime($time)
        // ]);

        Toastr::success("Làm mới tin thành công");
        return back();
    }

    // xóa 1 item
    public function trash_item($id){
        $classified = Classified::findOrFail($id);
        $classified->delete();
        // Helper::create_admin_log(26,[
        //     'id'=>$id,
        //     'is_deleted'=>1,
        //     'updated_at' => time()
        // ]);

        Toastr::success("Xóa tin rao thành công");
        return back();
    }

    //khôi phục 1 item
    public function untrash_item($id){
        $classified = Classified::onlyIsDeleted()->findOrFail($id);
        $classified->restore();
        // Helper::create_admin_log(27,[
        //     'id'=>$id,
        //     'is_deleted'=>0,
        //     'updated_at' => time()
        // ]);

        Toastr::success("Khôi phục tin rao thành công");
        return back();
    }

    // Nâng cấp vip
    public function upgrade_vip($id){
        $classified = Classified::findOrFail($id);
        $classified->update([
            'is_vip'=>1,
            'vip_begin' => time(),
            'vip_end'=>strtotime(Carbon::now()->addDay(1)),
            'updated_at' => time()
        ]);
        // Helper::create_admin_log(28,[
        //     'id'=>$id,
        //     'is_vip'=>1,
        //     'vip_begin' => time(),
        //     'vip_end'=>strtotime(Carbon::now()->addDay(1)),
        //     'updated_at' => time()
        // ]);

        Toastr::success("Nâng cấp vip thành công");
        return back();
    }

    // nâng cấp nổi bật
    public function upgrade_highlight($id){
        $classified = Classified::findOrFail($id);

        $classified->update([
            'is_hightlight'=>1,
            'hightlight_begin' => time(),
            'hightlight_end'=>strtotime(Carbon::now()->addDay(1)),
            'updated_at' => time()
        ]);
        // Helper::create_admin_log(29,[
        //     'id'=>$id,
        //     'is_hightlight'=>1,
        //     'hightlight_begin' => time(),
        //     'hightlight_end'=>strtotime(Carbon::now()->addDay(1)),
        //     'updated_at' => time()
        // ]);

        Toastr::success("Nổi bật tin rao thành công");
        return back();
    }

    // thay đổi trạng thái
    public function change_status($status, $id)
    {
        // should change to post method
        $classified = Classified::findOrFail($id);

        if($status !=3 ){
            // khong duyet update unapproved_at = now()
            $unapprovedAt = $status == 2 ? now() : $classified->unapproved_at;

            // duyệt lần đầu trừ số tin đăng
            if ($status == 1 && !$classified->confirmed_by && $classified->user) {
                $classifiedPackage = $classified->userBalance;

                if ($classifiedPackage) {
                    if ($classified->isVip()) {
                        $classifiedPackage->decrement('vip_amount', min(1, $classifiedPackage->vip_amount));
                    } elseif ($classified->isHighLight()) {
                        $classifiedPackage->decrement('highlight_amount', min(1, $classifiedPackage->highlight_amount));
                    } elseif ($classifiedPackage->package_id == 1) {
                        $classifiedPackage->decrement('classified_normal_amount', min(1, $classifiedPackage->classified_normal_amount));
                    }
                }
            }

            $classified = $classified->update([
                'confirmed_status' => $status,
                'confirmed_by'=>Auth::guard('admin')->user()->id,
                'updated_at' => time(),
                'unapproved_at' => $unapprovedAt,
            ]);
        }
        // Helper::create_admin_log(30,[
        //     'id'=>$id,
        //     'confirmed_status' => $status,
        //     'confirmed_by'=>Auth::guard('admin')->user()->id,
        //     'updated_at' => time()
        // ]);

        Toastr::success("Cập nhật trạng thái thành công");
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Classified::query()
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

        Classified::onlyIsDeleted()
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

        Classified::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                foreach ($item->children()->withIsDeleted()->get() as $child) {
                    $child->children()->withIsDeleted()->each(function($grandChild) {
                        $grandChild->forceDelete();
                    });
                    $child->forceDelete();
                }
                $item->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function edit_classified($id){
        $classified = Classified::findOrFail($id);
        $classified = Classified::query()
            ->leftJoin('classified_location','classified.id','=','classified_location.classified_id')
            ->leftJoin('group','classified.group_id','group.id')
            ->leftJoin('group as group_parent','group.parent_id','group_parent.id')
            ->leftJoin('group as group_parent_parent','group_parent.parent_id','group_parent_parent.id')
            ->select('group.group_name','group_parent.id as group_parent_id','group_parent_parent.id as group_parent_parent_id','classified.*','classified_location.address','classified_location.province_id','classified_location.district_id','classified_location.ward_id','classified_location.map_longtitude','classified_location.map_latitude')->where('classified.id',$id)->first();

            $classified_type ="";
        if($classified->group_parent_parent_id??$classified->group_parent_id == 2) $classified_type ="nhadatban";
        elseif($classified->group_parent_parent_id??$classified->group_parent_id == 10 )$classified_type ="nhadatchothue";
        elseif($classified->group_parent_parent_id??$classified->group_parent_id == 19 )$classified_type ="canmua";
        else $classified_type ="canthue";
//        -----------------------Data select----------------------------------
        // dữ liệu dự án
        $project  = Project::showed()->get();
           // số phòng ngủ
        $phongngu = ClassifiedParam::where('param_type','B')->get();
        // phòng vệ sinh
        $phongvesinh = ClassifiedParam::where('param_type','T')->get();
         // người tối đa
        $nguoitoida = ClassifiedParam::where('param_type','P')->get();
         // Cọc trước
        $coctruoc = ClassifiedParam::where('param_type','A')->get();
        // hướng nhà
        $huong = Direction::showed()->orderBy('show_order')->get();
        // pháp lý
        $phaply = ClassifiedParam::where('param_type','L')->get();
        // giá nhà đất bán
        $gianhadatban = Unit::showed()->where('unit_type','C')->take(6)->get();
        // giá nhà đất cho thue
//        $gianhadatchothue = DB::table('unit')->where('unit_type','C')->orderBy('id','desc')->take(4)->get();
        // tỉnh/ thành phố
        $tinh = Province::showed()->oldest('province_name')->get();

        // get properties
        $properties = Property::where('properties_type',0)->pluck('name')->toArray();

        return view('Admin.Classified.ListClassified.EditClassified',compact('properties',
            'project','phongngu','phongvesinh','huong','phaply','gianhadatban','nguoitoida','coctruoc','tinh','classified','classified_type'));
    }

    public function post_edit_classified(ClassifiedUpdateRequest $request,$id){
        $classified = Classified::findOrfail($id);

        // check list image
        $image_array = [];
        if(json_decode($request->image_url)){
            $image_array = json_decode($request->image_url);
        }
        else {
            array_push(  $image_array,$request->image_url) ;
        }
        if ($request->image_url_order != null) {
            $list_order = json_decode($request->image_url_order);
            $new_image_list = [];
            foreach ($list_order as $value) {
                $new_image_list[] = $image_array[$value];
            }
            $image_array = $new_image_list;
        }
        $data = [
                'group_id'=>$request->group_id,
                'project_id'=>$request->project,
                'classified_name'=>$request->title,
                'classified_description'=>$request->content,
                'classified_url'=>Str::slug($request->title),
                'classified_area'=>$request->classified_area,
                'area_unit_id'=>$request->dientich,
                'classified_price'=>$request->giaban,
                'price_unit_id'=>$request->donviban,
                'classified_direction'=>$request->huong,
                'num_bed'=>$request->phongngu,
                'num_toilet'=>$request->phongvesinh,
                'classified_juridical'=>$request->phaply,
                'classified_progress'=>$request->tinhtrang,
                'classified_furniture'=>$request->noithat,
                'contact_name'=>$request->contact_name,
                'contact_email'=>$request->contact_email,
                'contact_phone'=>$request->contact_phone,
                'contact_address'=>$request->contact_address,
                'image_perspective'=>$image_array,
                'video'=>$request->video_url,
                'meta_title' => $request->meta_title,
                'meta_key' => $request->meta_key,
                'meta_desc' => $request->meta_desc,
            ];

        if($request->has('is_mezzanino')) $data['is_mezzanino']=1;
        if($request->has('is_internet')) $data['is_internet']=1;
        if($request->has('is_balcony')) $data['is_balcony']=1;
        if($request->has('is_freezer')) $data['is_freezer']=1;
        $data['num_people']=$request->nguoitoida;
        $data['advance_stake']=$request->coctruoc;
//          if($request->has('is_monopoly')) $data['is_monopoly']=1;
//         if($request->has('is_broker')) $data['is_broker']=1;
//        if($request->group_parent == "nhadatchothue"||$request->group_parent == "canthue"){
//                if($request->has('is_mezzanino')) $data['is_mezzanino']=1;
//                if($request->has('is_internet')) $data['is_internet']=1;
//                if($request->has('is_balcony')) $data['is_balcony']=1;
//                if($request->has('is_freezer')) $data['is_freezer']=1;
//                $data['num_people']=$request->nguoitoida;
//                $data['advance_stake']=$request->coctruoc;
//        }
//        if($request->duan != -1) $data['project_id'] = $request->duan;
        $location = [
                'address'=>$request->duong,
                'province_id'=>$request->tinh,
                'district_id'=>$request->huyen,
                'ward_id'=>$request->xa,
                'map_longtitude'=>$request->map_longtitude,
                'map_latitude'=>$request->map_latitude,
        ];
        $classified->update($data);
        $classified->location()->updateOrCreate([], $location);
        // Helper::create_admin_log(31,$data);
        Toastr::success("Cập nhật thành công");
        return redirect()->route('admin.classified.list');
    }

    // preview nhà đất bán
            public function preview_nhadatban(Request  $request){

                $data = $request->data;
                $data = self::objectToArray($data);

                // get properties
                $properties = getCacheProperties();

                $items = new Classified();

                $location = new ClassifiedLocation();
                $location->district_id = $data['huyen'];
                $location->province_id = $data['tinh'];
                $location->address = $data['duong'];
                $location->map_longtitude = $data['map_longtitude'];
                $location->map_latitude = $data['map_latitude'];
//                $location->classified_id = 999;

                $items->project_id = $data['project']??null;
                $items->group_id = $data['group_id'];
                $items->district_id = $data['huyen'];
                $items->province_id = $data['tinh'];
                $items->location = $location;
                $items->price_unit_id =$data['donviban'];
                $items->classified_price = $data['giaban'];
                $items->classified_area =$data['classified_area'] ;
                $items->area_unit_id = $data['dientich'];
                $items->classified_direction = $data['huong'];

                $items->is_monopoly = isset($data['is_monopoly'])?1:0;
                $items->is_broker = isset($data['is_broker'])?1:0;
                $items->num_bed =$data['phongngu'];
                $items->num_toilet =$data['phongvesinh'];
                $items->num_people =$data['nguoitoida']??null;;
                $items->classified_juridical =$data['phaply']??null;
                $items->advance_stake =$data['coctruoc']??null;
                $items->classified_progress =$data['tinhtrang']??null;
                $items->classified_furniture =$data['noithat']??null;
                $items->is_mezzanino =isset($data['is_mezzanino'])?1:0;;
                $items->is_internet =isset($data['is_internet'])?1:0;
                $items->is_balcony =isset($data['is_balcony'])?1:0;
                $items->is_freezer =isset($data['is_freezer'])?1:0;
                $items->created_at =time();

                // get đơn vị giá
                $donviban = Unit::where('id',$data['donviban'])->first();
                $data['donviban'] = $donviban->unit_name;
                $data['group'] = $data['group_parent'];
//
//
                // get dự án
                if($data['project']!= null){
                    $project = Project::find($data['project']);
                    $data['project'] = data_get($project, 'project_name');
                }

                // get mô hình
                if($data['group_id']!=null){
                    $mohinh =  DB::table('group')->where('id',$data['group_id'])->first();
                    $data['group_id'] = $mohinh->group_name;
                }
                // get tỉnh
                if($data['tinh']!="-1"){
                    $tinh =  DB::table('province')->where('id',$data['tinh'])->first();
                    $data['tinh'] = $tinh->province_name;
                }
                // get quận huyện
                if($data['huyen']!=-1){
                    $huyen =  DB::table('district')->where('id',$data['huyen'])->first();
                    $data['huyen'] = $huyen->district_name;
                }
                // get phòng ngủ
                if($data['phongngu']!=-1){
                    $phongngu =  ClassifiedParam::where('id',$data['phongngu'])->first();
                    $data['phongngu'] = $phongngu->param_name;
                }
                // get phòng vệ sinh
                if($data['phongvesinh']!=-1){
                    $phongvesinh =  ClassifiedParam::where('id',$data['phongvesinh'])->first();
                    $data['phongvesinh'] = $phongvesinh->param_name;
                }
                // get ở tối đa
                if($data['nguoitoida']!=-1){
                    $nguoitoida =  ClassifiedParam::where('id',$data['nguoitoida'])->first();
                    $data['nguoitoida'] = $nguoitoida->param_name;
                }
                // get cọc trước
                if($data['coctruoc']!=-1){
                    $coctruoc =  ClassifiedParam::where('id',$data['coctruoc'])->first();
                    $data['coctruoc'] = $coctruoc->param_name;
                }
                // get tình trạng
                if($data['tinhtrang']!=-1){
                    $tinhtrang =  DB::table('progress')->where('id',$data['tinhtrang'])->first();
                    $data['tinhtrang'] = $tinhtrang->progress_name;
                }
                //get hướng
                if($data['huong']!=-1){
                    $huong =  DB::table('direction')->where('id',$data['huong'])->first();
                    $data['huong'] = $huong->direction_name;
                }
                //get pháp lý
                if($data['phaply']!=-1){
                    $phaply =  ClassifiedParam::where('id',$data['phaply'])->first();
                    $data['phaply'] = $phaply->param_name;
                }
                //get nội thất
                if(isset($data['noithat'])){
                    $noithat = DB::table('furniture')->where('id',$data['noithat'])->first();
                    $data['noithat']= $noithat->furniture_name;
                }
                // check list image
                $image_array = [];
                if(json_decode($data['image_url'])){
                    $image_array = json_decode($data['image_url']);
                }
                else {
                  array_push(  $image_array,$data['image_url']) ;
                }
                if ($data['image_url_order'] != null) {
                    $list_order = json_decode($data['image_url_order']);
                    $new_image_list = [];
                    foreach ($list_order as $value) {
                        $new_image_list[] = $image_array[$value];
                    }
                    $image_array = $new_image_list;
                }
                // get hình ảnh
                $data['hinhanh'] = json_encode($image_array);
                $items->image_perspective = $data['hinhanh'];
//
                $html = view('Admin.Classified.Component.nhadatban',compact('items','data','properties'))->render();

            return response($html);
    }

    // toggle classified
    public function toggle_classified($id){
        $classified =  Classified::find($id);

        if (!$classified){
            Toastr::error("Không tìm thấy tin đăng");
            return redirect()->back();
        }else{
            Classified::where('id', $id)->update([
               'is_show' => !$classified->is_show
            ]);
            Toastr::success("Thành công");
            return redirect()->back();
        }
    }

    /////------------------------------------------------------------------------OTHER METHOD------------------------------------------------------------------------//
    public function objectToArray($input) : array
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
