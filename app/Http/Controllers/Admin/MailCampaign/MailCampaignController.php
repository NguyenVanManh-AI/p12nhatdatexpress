<?php

namespace App\Http\Controllers\Admin\MailCampaign;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendEmailCampaignNow;
use App\Models\AdminMailCampaign;
use App\Models\AdminMailCampaignDetail;
use App\Models\AdminMailConfig;
use App\Models\AdminMailTemplate;
use App\Models\Classified\Classified;
use App\Models\Group;
use App\Models\Province;
use App\Models\User;
use App\Models\User\UserType;

class MailCampaignController extends Controller
{
    private const ARRAY_GROUP = [2,10,18];
    //----------------------------------------------------------EDIT--------------------------------------------------//
    # Giao diện chỉnh sửa chiến dịch email
    public function edit_mail_campaign($id){
        $checkCampaign = AdminMailCampaign::where('id',$id)->first();
        //kiểm tra nếu tồn tại chiến dịch có id truyền vào
        if($checkCampaign == null){
            return redirect()->route('admin.email-campaign.list-campaign');
        }else{
            //kiểm tra loại chiến dịch nếu gửi ngay hoặc đã thực hiện thì không được sửa
            if($checkCampaign->is_action == 1 || $checkCampaign->total_receipt_mail > 0){
                Toastr::error("Chiến dịch đã được thực hiện. Sửa thất bại");
                return redirect()->route('admin.email-campaign.list-campaign');
            }
        }
        // Lấy ra chiến dịch có id truyền vào
        $getCampaign = AdminMailCampaign::query()
        ->join('admin_mail_template','admin_mail_template.id','admin_mail_campaign.mail_template_id')
        ->leftJoin('group','group.id','admin_mail_campaign.group_id')
        ->leftJoin('province','province.id','admin_mail_campaign.province_id')
        ->leftJoin('user_type','user_type.id','admin_mail_campaign.account_type')
        ->select('admin_mail_campaign.*','admin_mail_template.template_title','group.group_name','province.province_name','user_type.type_name')
        ->where('admin_mail_campaign.id',$id)
        ->first();
        //lấy ra các user được chọn
        $getUserSelect =null;
        if($getCampaign->campaign_date_to == null){
             $getUserSelect = AdminMailCampaign::query()
             ->where('admin_campaign_id',$id)
             ->select('admin_mail_campaign_detail.user_id')
             ->get();
        }
        //lấy ra mẫu email
        $templateEmail = AdminMailTemplate::query()
        ->orderBy('admin_mail_template.id', 'desc')
        ->get();
        //vị trí cho lọc
        $province  = Province::showed()->get();
        //loại user (cá nhân, doanh nghiệp, cvtv)
        $userType = UserType::showed()->get();
        //lấy ra toàn bộ user để bỏ vào select
        $getuser = User::query()
            ->select('user.id', 'username', 'user.email', 'user_detail.fullname')
            ->join('user_detail','user_detail.user_id','user.id')
            ->orderBy('user.id', 'desc')
            ->get();
        //lấy ra chuyên mục cho lọc
        $group =  Group::whereIn('id', self::ARRAY_GROUP)->select('id', 'group_name')->get();

        return view('Admin.MailCampaign.EditMailCampaign',compact('getCampaign','templateEmail','getuser','province','getUserSelect','group','userType'));
    }

    # Sửa chiến dịch nếu có user được chọn
    public function edit_send_filter_user($id, Request $request): RedirectResponse
    {
        $checkCampaign = AdminMailCampaign::where('id',$id)->first();
        if($checkCampaign->is_action == 1){
            return redirect()->route('admin.email-campaign.list-campaign');
        }
        if($this->check_template($request->mail_template_id) < 1){
            return redirect()->route('admin.email-campaign.list-campaign');
        }

        //kiểm tra id tồn tại
        if(isset($id))
            AdminMailCampaign::where('admin_campaign_id', $id)->delete();

        //nếu từ ngày khác rổng
        $request->from_date = $request->from_date == "" ? null : date(strtotime($request->from_date));
        $request->to_date = $request->to_date == "" ? null : date(strtotime($request->to_date));

        $getUser = $this->get_users($request);

        $insertGetId = $id;
        //kiểu gửi
        $type_send = $this->validate_mail_campaign($request);

        $data = [
            'campaign_name' => $request->campaign_name,
            'mail_template_id' => $request->mail_template_id,
            'total_mail' => count($getUser),
            'total_receipt_mail' => 0,
            'group_id' => $request->classified_category,
            'province_id' => $request->province,
            'campaign_date_to' => 1,
            'is_birthday' => 0,
            'account_type' => $request->type_account,
            'created_from' => $request->from_date,
            'created_to' => $request->to_date,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->id()
        ];
        $this->update_data_mail_campaign($request, $data, $type_send, $getUser, $id);

        //chuyển hướng đến trang chi tiết gửi
        return redirect()->route('admin.mail-campaign.list-send-mail', $insertGetId);
    }

    # Sửa chiến dịch cho list user
    public function edit_send_list_user($id,Request $request): RedirectResponse
    {
        if($this->check_template($request->mail_template_id) < 1){
            return back();
        }
        if(isset($id))
            $deleteCampaignDetail = AdminMailCampaign::where('admin_campaign_id',$id)->delete();

        //kiểu gửi
        $type_send = $this->validate_mail_campaign($request);

        $data = [
            'campaign_name' => $request->campaign_name,
            'mail_template_id' => $request->mail_template_id,
            'total_mail' => count($request->list_user),
            'total_receipt_mail' => 0,
            'start_date' => null,
            'start_time' => null,
            'campaign_date_to' => null,
            'group_id' => null,
            'province_id' => null,
            'account_type' => null,
            'created_from' => null,
            'created_to' => null,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->id()
        ];

        $this->update_data_mail_campaign($request, $data, $type_send, $request->list_user, $id);

        //chuyển hướng đến trang chi tiết gửi
        return redirect()->route('admin.mail-campaign.list-send-mail', $id);
    }

    //--------------------------------------------------------CREATE--------------------------------------------------//
	# Tạo chiến dịch cho list user được chọn từ combobox
    public function send_list_user(Request $request): RedirectResponse
    {
        if ($this->check_template($request->mail_template_id) < 1) {
            return back();
        }

        $type_send = $this->validate_mail_campaign($request);
        $data = [
            'campaign_name' => $request->campaign_name,
            'mail_template_id' => $request->mail_template_id,
            'total_mail' => count($request->list_user),
            'total_receipt_mail' => 0,
            'campaign_date_to' => null,
            'created_by' => Auth::guard('admin')->id(),
            'created_at' => time(),
        ];

        $insertGetId = $this->insert_data_mail_campaign($request, $data, $type_send, $request->list_user);

        return $insertGetId != null
            ? redirect()->route('admin.mail-campaign.list-send-mail', $insertGetId)
            : back();
    }

    # Tạo chiến dịch cho tất cả user
    public function send_filter_user(Request $request){

        $request->from_date = $request->from_date == "" ? null : date(strtotime($request->from_date));
        $request->to_date = $request->to_date == "" ? null : date(strtotime($request->to_date));

        if($this->check_template($request->mail_template_id) < 1){
            return back();
        }

        $getUser = $this->get_users($request);
        $type_send = $this->validate_mail_campaign($request);

        $data = [
            'campaign_name' => $request->campaign_name,
            'mail_template_id' => $request->mail_template_id,
            'total_mail' => count($getUser),
            'total_receipt_mail' => 0,
            'group_id' => $request->classified_category,
            'province_id' => $request->province,
            'campaign_date_to' => 1,
            'account_type' => $request->type_account,
            'created_from' => $request->from_date,
            'created_to' => $request->to_date,
            'created_at' => time(),
            'created_by' => Auth::guard('admin')->id()
        ];

        $insertGetId = $this->insert_data_mail_campaign($request, $data, $type_send, $getUser);

        return $insertGetId != null
            ? redirect()->route('admin.mail-campaign.list-send-mail', $insertGetId)
            : back();
    }

    //--------------------------------------------------------DELETE--------------------------------------------------//
	# Xóa chiến dịch email
    public function delete_mail_campaign($id): RedirectResponse
    {
		//tìm mẫu mail cần xóa theo id
        $campaign = AdminMailCampaign::findOrFail($id);
        $campaign->delete();

        // $data =  [
        //     'is_deleted' => 1,
        //     'updated_at' => time(),
        //     'updated_by'=>Auth::guard('admin')->id()
        // ];
        // Helper::create_admin_log(189, array_merge([
        //     'id'=>$id,
        // ], $data));

        Toastr::success('Chuyển vào thùng rác thành công');
        return back();
    }

    //--------------------------------------------------------CREATE--------------------------------------------------//
	# Khôi phục chiến dịch email
    public function un_delete_mail_campaign($id): RedirectResponse
    {
        $campaign = AdminMailCampaign::onlyIsDeleted()->findOrFail($id);
        $campaign->restore();
        // $data =  [
        //     'is_deleted' => 0,
        //     'updated_at' => time(),
        //     'updated_by'=>Auth::guard('admin')->id()
        // ];

        // Helper::create_admin_log(190, array_merge([
        //     'id'=>$id,
        // ], $data));

        Toastr::success('Khôi phục thành công');
        return back();
    }

	//xóa nhiều mẫu mail
    public function delete_mail_campaign_list(Request $request){
    	//nếu danh dách item truyền vào là null
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        //lặp các id mẫu mail được truyền vào
        foreach ($request->select_item as $id) {
            $campaign = AdminMailCampaign::find($id);
            if (!$campaign) continue;

            $campaign->delete();
            // $data = [
            //     'id'=>$item,
            //     'is_deleted' => 1,
            //     'updated_at' => time(),
            //     'updated_by'=>Auth::guard('admin')->id()
            // ];
            // Helper::create_admin_log(189,$data);
        }

        Toastr::success('Chuyển vào thùng rác thành công');
        return back();
    }

   	//khôi phục nhiều bình mẫu mail
    public function un_delete_mail_campaign_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $id) {
            $campaign = AdminMailCampaign::onlyIsDeleted()->find($id);
            if (!$campaign) continue;

            $campaign->restore();
            // $data = [
            //     'id'=>$item,
            //     'is_deleted' => 0,
            //     'updated_at' => time(),
            //     'updated_by'=>Auth::guard('admin')->id()
            // ];
            // Helper::create_admin_log(190,$data);
        }

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        AdminMailCampaign::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();

                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

   	# Danh sách chiến dịch email (Primary)
    public function list_campaign(Request $request){
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)){
            $items = $request->items;
        }

        //phân quyền
        $listQuery = AdminMailCampaign::query()
            ->orderBy('admin_mail_campaign.id','desc')
            ->join('admin_mail_template', 'admin_mail_template.id', '=', 'admin_mail_campaign.mail_template_id');

        if ($request->request_list_scope == 2) { // nhóm của tôi
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listQuery = $listQuery
                ->join('admin', 'admin_mail_campaign.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->select('admin_mail_campaign.id','admin_mail_campaign.created_by','admin_mail_campaign.created_at','admin_mail_campaign.campaign_name','admin_mail_campaign.total_mail','admin_mail_campaign.total_receipt_mail','admin_mail_campaign.total_mail','admin_mail_campaign.start_date','admin_mail_campaign.created_by','admin_mail_campaign.is_birthday','admin_mail_template.template_title');
        }else if ($request->request_list_scope == 3) { //chỉ mình tôi
            //lấy id admin đang đăng nhập
            $admin_id = Auth::guard('admin')->id();
            $listQuery = $listQuery
                ->where('admin_mail_campaign.created_by',$admin_id)
                ->orderBy('admin_mail_campaign.id','desc')
                ->select('admin_mail_campaign.id','admin_mail_campaign.created_by','admin_mail_campaign.created_at','admin_mail_campaign.campaign_name','admin_mail_campaign.total_mail','admin_mail_campaign.total_receipt_mail','admin_mail_campaign.total_mail','admin_mail_campaign.start_date','admin_mail_campaign.created_by','admin_mail_campaign.is_birthday','admin_mail_template.template_title');
        }

        $listQuery = $listQuery->select('admin_mail_campaign.id','admin_mail_campaign.created_by','admin_mail_campaign.created_at','admin_mail_campaign.campaign_name','admin_mail_campaign.total_mail','admin_mail_campaign.total_receipt_mail','admin_mail_campaign.total_mail','admin_mail_campaign.start_date','admin_mail_campaign.created_by','admin_mail_campaign.is_birthday','admin_mail_template.template_title', 'is_action');

        //lọc theo từ khóa
        if($request->keyword){
        	$listQuery->where('admin_mail_campaign.campaign_name', 'like', '%' . $request->keyword. '%');
        }
        //lọc theo từ ngày
        if($request->from_date){
        	$listQuery->where('admin_mail_campaign.created_at','>',date(strtotime($request->from_date)));
        }
         //lọc theo đến ngày
        if($request->to_date){
        	$listQuery->where('admin_mail_campaign.created_at','<',date(strtotime($request->to_date)));
        }
        //lấy ra danh sách và phân trang
        $list = $listQuery->where('is_customer', 0)->paginate($items);

        $trash_num = AdminMailCampaign::where('is_customer', 0)->onlyIsDeleted()->count();

        return view('Admin.MailCampaign.ListMailCampaign', compact('list', 'trash_num'));
    }

    //danh sách chiến dịch email
    public function trash_list_campaign(Request $request){
		//lấy ra 10 dòng
        $items = 10;
		//nếu có request từ url và items là số
        if ($request->has('items') && is_numeric($request->items)){
			// lấy ra số dòng tương ứng
            $items = $request->items;
        }
    	//phân quyền
    	if ($request->request_list_scope == 2) { // nhóm của tôi
    	   //lấy role_id phân quyền
    		$admin_role_id = Auth::guard('admin')->user()->rol_id;
    		$listQuery = AdminMailCampaign::query()
    		->orderBy('admin_mail_campaign.id','desc')
    		->join('admin', 'admin_mail_campaign.created_by', '=', 'admin.id')
    		->join('admin_mail_template', 'admin_mail_template.id', '=', 'admin_mail_campaign.mail_template_id')
    		->where('admin.rol_id', $admin_role_id)
    		->select('admin_mail_campaign.id','admin_mail_campaign.created_by','admin_mail_campaign.created_at','admin_mail_campaign.campaign_name','admin_mail_campaign.total_mail','admin_mail_campaign.total_receipt_mail','admin_mail_campaign.total_mail','admin_mail_campaign.start_date','admin_mail_campaign.created_by','admin_mail_campaign.is_birthday','admin_mail_template.template_title');
        }else if ($request->request_list_scope == 3) { //chỉ mình tôi
        	//lấy id admin đang đăng nhập
        	$admin_id = Auth::guard('admin')->id();
        	//select bảng admin_mail_template
        	$listQuery = AdminMailCampaign::query()
        	->join('admin_mail_template', 'admin_mail_template.id', '=', 'admin_mail_campaign.mail_template_id')
        	//điều kiện is_deleted =0
        	//điều kiện created_by = id của admin đang đăng nhập
        	->where('admin_mail_campaign.created_by',$admin_id)
        	//hiển thị theo thứ tự của show_order từ cao đến thấp
        	->orderBy('admin_mail_campaign.id','desc')
        	->select('admin_mail_campaign.id','admin_mail_campaign.created_by','admin_mail_campaign.created_at','admin_mail_campaign.campaign_name','admin_mail_campaign.total_mail','admin_mail_campaign.total_receipt_mail','admin_mail_campaign.total_mail','admin_mail_campaign.start_date','admin_mail_campaign.created_by','admin_mail_campaign.is_birthday','admin_mail_template.template_title');
        }else { //tất cả
        	//select bảng admin_mail_template
        	$listQuery = AdminMailCampaign::query()
        	->orderBy('admin_mail_campaign.id','desc')
        	->join('admin_mail_template', 'admin_mail_template.id', '=', 'admin_mail_campaign.mail_template_id')
        	->select('admin_mail_campaign.id','admin_mail_campaign.created_by','admin_mail_campaign.created_at','admin_mail_campaign.campaign_name','admin_mail_campaign.total_mail','admin_mail_campaign.total_receipt_mail','admin_mail_campaign.total_mail','admin_mail_campaign.start_date','admin_mail_campaign.created_by','admin_mail_campaign.is_birthday','admin_mail_template.template_title');
        }
     	//lọc theo từ khóa
        if($request->keyword){
        	//điều kiện từ khóa có trong template_title
        	$listQuery->where('admin_mail_campaign.campaign_name', 'like', '%' . $request->keyword. '%');
        }
        //lọc theo từ ngày
        if($request->from_date){
        	//điều kiện từ ngày nhỏ hơn created_at
        	$listQuery->where('admin_mail_campaign.created_at','>',date(strtotime($request->from_date)));
        }
         //lọc theo đến ngày
        if($request->to_date){
        	//điều kiện đến ngày lớn hơn created_at
        	$listQuery->where('admin_mail_campaign.created_at','<',date(strtotime($request->to_date)));
        }
        //lấy ra danh sách và phân trang
        $list = $listQuery->onlyIsDeleted()->paginate($items);

        return view('Admin.MailCampaign.TrashListMailCampaign', compact('list'));
    }

    # Danh sách mail gửi
    public function list_send_mail(Request $request, $id){
    	$items = $request->items ?? 10;

        $getCampaign = AdminMailCampaign::where('admin_mail_campaign.id',$id)->first();
        if($getCampaign == null){
            return redirect()->back();
        }
            $listMailSend = AdminMailCampaignDetail::query()
            ->join('admin_mail_config','admin_mail_config.id','admin_mail_campaign_detail.admin_mail_config_id')
            ->join('admin_mail_campaign','admin_mail_campaign.id','admin_mail_campaign_detail.admin_campaign_id')
            ->where('admin_mail_campaign_detail.admin_campaign_id', $id)
            ->select('admin_mail_campaign_detail.id','admin_mail_campaign_detail.user_email','admin_mail_campaign_detail.receipt_status','admin_mail_campaign_detail.receipt_time','admin_mail_campaign_detail.created_by','admin_mail_config.mail_username')
            ->paginate($items);

        return view('Admin/MailCampaign/HistorySendMail', compact('listMailSend'));
    }

    # Giao diện thêm chiến dịch email
    public function add_mail_campaign(){
    	$templateEmail = AdminMailTemplate::query()
        ->orderBy('id', 'desc')
        ->get();

        $province  = Province::showed()->select('id', 'province_name')->get();
        $getuser = User::query()
            ->select('user.id', 'username', 'user.email', 'user_detail.fullname')
            ->join('user_detail','user_detail.user_id','user.id')
            ->orderBy('user.id', 'desc')
            ->get();

        $user_type = UserType::showed()->get();

        return view('Admin.MailCampaign.AddMailCampaign',compact('templateEmail','getuser', 'province', 'user_type'));
    }

    //-----------------------------------------------------------SUPPORT----------------------------------------------//
    # Check template
    private function check_template($id) : int
    {
        return AdminMailTemplate::query()
            ->where('admin_mail_template.id', $id)
            ->count();
    }

    # Validate
    private function validate_mail_campaign(Request $request): string
    {
        $translator = [
            'campaign_name.required' => 'Tên chiến dịch không được bỏ trống',
            'campaign_name.min' => 'Tên chiến dịch tối thiểu 1 ký tự',
            'campaign_name.max' => 'Tên chiến dịch tối đa 255 ký tự',
            'start_date.required' => 'Ngày bắt đầu không được bỏ trống',
            'start_time_hour.required' => 'Giờ không được bỏ trống',
            'start_time_min.required' => 'Phút không được bỏ trống',
            'mail_template_id.required' => 'Mẫu email không được bỏ trống',
            'start_time_min.between' => 'Phút phải là từ 00 - 59',
            'start_time_hour.between' => 'Phút phải là từ 00 - 23',
        ];

        if (isset($request->is_birthday)) {
            // Đến ngày sinh nhật thì gửi
            $type_send = "send_birthday";
            $request->validate([
                'campaign_name' => 'required|min:1|max:250',
                'mail_template_id' => 'required|integer',
            ], $translator);
        } else if (isset($request->start_date)) {
            //đặt thời gian gửi cụ thể
            $type_send = "send_set_time";
            $request->validate([
                'campaign_name' => 'required|min:1|max:250',
                'mail_template_id' => 'required|integer',
                'start_date' => 'required',
                'start_time_hour' => 'required|between:0,23',
                'start_time_min' => 'required|between:0,59',
            ], $translator);
        } else {
            //gửi ngay
            $type_send = "send_now";
            $request->validate([
                'campaign_name' => 'required|min:1|max:250',
                'mail_template_id' => 'required|integer',
            ], $translator);
        }
        return $type_send;
    }

    # Insert detail mail campaign
    private function insert_detail_mail_campaign($list_user, $insertGetId){
        if ($list_user) {
            //lấy ra các email cấu hình của account đang đăng nhập
            $listMailConfig = AdminMailConfig::get();
            //tạo biến i =0
            $i2 = 0;

            //lặp tất cả user đó
            for ($i = 1; $i <= count($list_user); $i++) {
                if ($listMailConfig->count() == $i2) {
                    $i2 = 1;
                } else {
                    $i2++;
                }

                //lấy id từng user
                $user = User::query()
                    ->select('user.id', 'user.email')
                    ->join('user_detail', 'user_detail.user_id', 'user.id')
                    ->where('user.id', $list_user[$i - 1])
                    ->first();

                if (!$user) continue;

                //insert vào bảng admin_mail_campaign_detail
                AdminMailCampaignDetail::create([
                        'admin_campaign_id' => $insertGetId,
                        'admin_mail_config_id' => $listMailConfig[$i2 - 1]->id,
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'created_by' => Auth::guard('admin')->id()
                    ]
                );
            }
        }
    }

    # Get user if you don't select user in select box
    private function get_users($request): Collection
    {
        //nếu danh mục có
        if(isset($request->classified_category) != null){
            //lấy tất cả user đã đăng tin tại danh mục này
            $getUserQuery = Classified::query()
                ->join('user','user.id','classified.user_id')
                ->join('user_detail','user_detail.user_id','=','user.id')
                ->leftjoin('group','group.id','=','classified.group_id')
                ->leftjoin('user_type','user.user_type_id','=','user_type.id')
                ->leftJoin('user_location','user_location.user_id','=','user.id')
                ->leftJoin('province','user_location.province_id','=','province.id')
                ->distinct()
                ->select('user.id')
                ->where('group.parent_id',$request->classified_category);
        }else{
            //ngược lại thì lấy tất cả user
            $getUserQuery = User::query()
                ->join('user_detail','user_detail.user_id','=','user.id')
                ->leftjoin('user_type','user.user_type_id','=','user_type.id')
                ->leftJoin('user_location','user_location.user_id','=','user.id')
                ->leftJoin('province','user_location.province_id','=','province.id')
                ->select('user.id');
        }
        //nếu có lọc vị trí
        if(isset($request->province)){
            $getUserQuery->where('province_id',$request->province);
        }
        //nếu có loại tài khoản
        if(isset($request->type_account)){
            $getUserQuery->where('user_type.id',$request->type_account);
        }
        //nếu có tạo từ ngày
        if($request->from_date){
            $getUserQuery->where('user.created_at', '>', $request->from_date);
        }
        //nếu có tạo đến ngày
        if($request->to_date){
            $getUserQuery->where('user.created_at','<', $request->to_date + 86399);
        }

        //lấy ra danh sách kết quả
        return $getUserQuery->pluck('id');
    }

    # Update mail campaign
    private function update_data_mail_campaign(Request $request, $data, $type_send, $list_user, $id_campaign){

        if ($type_send == "send_birthday") {
            $data['is_birthday'] = 1;
        } else if ($type_send == "send_set_time") {
            //ngày giờ đặt lịch lấy từ input chuyển sang unixtime
            $data['start_date'] = date(strtotime($request->start_date . " " . $request->start_time_hour . ":" . $request->start_time_min . ":" . "00"));
            $data['start_time'] = $request->start_time_hour * 60 + $request->start_time_min;
        }

        try {
            DB::beginTransaction();
            $campaign = AdminMailCampaign::find($id_campaign);
            $campaign->update($data);
            $this->insert_detail_mail_campaign($list_user, $id_campaign);
            DB::commit();

            if ($type_send != 'send_set_time' && $type_send != 'send_birthday') {
                SendEmailCampaignNow::dispatch($id_campaign);
            }

            // if ($type_send == "send_set_time")
            //     Helper::create_admin_log(195, $data);
            // elseif ($type_send == "send_birthday")
            //     Helper::create_admin_log(197, $data);
            // else {
            //     Helper::create_admin_log(196, $data);
            //     SendEmailCampaignNow::dispatch($id_campaign);
            // }

        } catch (Exception $exception) {
            DB::rollBack();
            Toastr::error("Xảy ra lỗi không xác định");
        }
    }

    # Insert mail campagin
    private function insert_data_mail_campaign(Request $request, $data, $type_send, $list_user): ?int
    {
        $insertGetId = null;

        if ($type_send == "send_birthday") {
            $data['is_birthday'] = 1;
        }
        else if ($type_send == "send_set_time") {
            //ngày giờ đặt lịch lấy từ input chuyển sang unixtime
            $data['start_date'] = date(strtotime($request->start_date . " " . $request->start_time_hour . ":" . $request->start_time_min . ":" . "00"));
            $data['start_time'] = $request->start_time_hour * 60 + $request->start_time_min;
        }

        try {
            DB::beginTransaction();
            $campaign = AdminMailCampaign::create($data);
            $insertGetId = $campaign->id;
            $this->insert_detail_mail_campaign($list_user, $insertGetId);
            DB::commit();

            if ($type_send != 'send_set_time' && $type_send != 'send_birthday') {
                SendEmailCampaignNow::dispatch($insertGetId);
            }

            // if ($type_send == "send_set_time")
            //     Helper::create_admin_log(186, $data);
            // else if ($type_send == "send_birthday")
            //     Helper::create_admin_log(188, $data);
            // else {
            //     Helper::create_admin_log(187, $data);
            //     SendEmailCampaignNow::dispatch($insertGetId);
            // }

        }catch (Exception $exception){
            DB::rollBack();
            Toastr::error("Xảy ra lỗi không xác định");
        }

        return $insertGetId;
    }
}
