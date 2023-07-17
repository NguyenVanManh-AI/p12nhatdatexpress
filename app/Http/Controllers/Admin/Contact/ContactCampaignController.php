<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Http\Controllers\Controller;
use App\Models\AdminMailCampaign;
use App\Models\AdminMailConfig;
use App\Models\AdminMailTemplate;
use App\Models\Group;
use App\Models\Province;
use App\Models\User;
use App\Models\User\CustomerParam;
use App\Models\User\UserType;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactCampaignController extends Controller
{
    private const ARRAY_GROUP = [2,10,18];

    //----------------------------------------------------------LIST--------------------------------------------------//
    # List campaign
    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }

        //phân quyền
        $listQuery = $this->get_campaigns($request);

        $listQuery = $listQuery
            ->select('admin_mail_campaign.id', 'admin_mail_campaign.created_by', 'admin_mail_campaign.created_at', 'admin_mail_campaign.campaign_name', 'admin_mail_campaign.total_mail', 'admin_mail_campaign.total_receipt_mail', 'admin_mail_campaign.total_mail', 'admin_mail_campaign.start_date', 'admin_mail_campaign.created_by', 'admin_mail_campaign.is_birthday', 'admin_mail_template.template_title', 'is_action');
        //lọc theo từ khóa
        if ($request->keyword) {
            $listQuery->where('admin_mail_campaign.campaign_name', 'like', '%' . $request->keyword . '%');
        }
        //lọc theo từ ngày
        if ($request->from_date) {
            $listQuery->where('admin_mail_campaign.created_at', '>', date(strtotime($request->from_date)));
        }
        //lọc theo đến ngày
        if ($request->to_date) {
            $listQuery->where('admin_mail_campaign.created_at', '<', date(strtotime($request->to_date)));
        }
        //lấy ra danh sách và phân trang
        $list = $listQuery->paginate($items);

        $trash_num = $this->get_campaigns($request)->onlyIsDeleted()->count();

        return view('Admin.Contact.MailCampaign.ListMailCampaign', compact('list', 'trash_num'));
    }

    # List trash campaign
    public function trash_list_campaign(Request $request){
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)){
            $items = $request->items;
        }
        //phân quyền
        $listQuery = $this->get_campaigns($request);

        $list = $listQuery->onlyIsDeleted()
            ->select('admin_mail_campaign.id','admin_mail_campaign.created_by','admin_mail_campaign.created_at','admin_mail_campaign.campaign_name','admin_mail_campaign.total_mail','admin_mail_campaign.total_receipt_mail','admin_mail_campaign.total_mail','admin_mail_campaign.start_date','admin_mail_campaign.created_by','admin_mail_campaign.is_birthday','admin_mail_template.template_title', 'is_action')
            ->paginate($items);

        return view('Admin.Contact.MailCampaign.TrashListMailCampaign', compact('list'));
    }

    //--------------------------------------------------------CREATE--------------------------------------------------//
    # Create mail campaign
    public function campaign_mail(Request $request): JsonResponse
    {
        $type_send = $this->validate_mail_campaign($request);

        $contact_controller = app('App\Http\Controllers\Admin\Contact\ContactController');
        $getUser = $contact_controller->get_customer($request)->select('customer.id')->pluck('customer.id');

        $data = [
            'campaign_name' => $request->campaign_name,
            'mail_template_id' => $request->mail_template_id,
            'total_mail' => count($getUser),
            'is_customer' => 1,
            'total_receipt_mail' => 0,
            'group_id' => $request->group_child,
            'group_parent_id' => $request->group_id,
            'job_id' => $request->job,
            'cus_source' => $request->list_type,
            'province_id' => $request->province,
            'district_id' => $request->district,
            'campaign_date_to' => 1,
            'is_birthday' => 0,
            'birthday' => strtotime($request->birthday),
            'created_from' => strtotime($request->created_at),
            'created_to' => strtotime($request->created_at) + 86399,
            'created_at' => time(),
            'created_by' => Auth::guard('admin')->id()
        ];

        $insertGetId = $this->insert_data_mail_campaign($request, $data, $type_send);

        return $insertGetId != null
            ? response()->json(['url' => route('admin.contact.campaign.list', $insertGetId)])
            : response()->json(['url' => back()]);
    }

    //----------------------------------------------------------EDIT--------------------------------------------------//
    # edit
    public function edit_mail_campaign($id){
        $checkCampaign = AdminMailConfig::where('id',$id)->first();
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
            ->leftJoin('group','group.id','admin_mail_campaign.group_parent_id')
            ->leftJoin('province','province.id','admin_mail_campaign.province_id')
            ->leftJoin('user_type','user_type.id','admin_mail_campaign.account_type')
            ->select('admin_mail_campaign.*','admin_mail_template.template_title','group.group_name','province.province_name','user_type.type_name')
            ->where('admin_mail_campaign.id',$id)
            ->first();
        //lấy ra các khách hàng được chọn
        $getUserSelect =null;

        //lấy ra mẫu email
        $templateEmail =  AdminMailTemplate::latest('admin_mail_template.id')
            ->get();
        //vị trí cho lọc
        $province  = Province::showed()->select('province_name','id')->get();
        //loại user (cá nhân, doanh nghiệp, cvtv)
        $userType = UserType::get();
        //lấy ra toàn bộ user để bỏ vào select
        $getuser = User::query()
            ->select('user.id', 'username', 'user.email', 'user_detail.fullname')
            ->join('user_detail','user_detail.user_id','user.id')
            ->orderBy('user.id', 'desc')
            ->where('user.is_deleted','=',0)
            ->get();
        //lấy ra chuyên mục cho lọc
        $group =  Group::whereIn('id', self::ARRAY_GROUP)->select('id', 'group_name')->get();
        $job = CustomerParam::where(['param_type' => 'JB'])->get();
        $list_type = CustomerParam::where('param_type', 'CF')->get();

        return view('Admin.Contact.MailCampaign.EditMailCampaign',
            compact('getCampaign','templateEmail','getuser','province','getUserSelect','group','userType', 'job', 'list_type'));
    }

    # update
    public function update_mail_campaign(Request $request, $id): RedirectResponse
    {
        $type_send = $this->validate_mail_campaign($request);

        $contact_controller = app('App\Http\Controllers\Admin\Contact\ContactController');
        $getUser = $contact_controller->get_customer($request)->select('customer.id')->pluck('customer.id');

        $data = [
            'campaign_name' => $request->campaign_name,
            'mail_template_id' => $request->mail_template_id,
            'total_mail' => count($getUser),
            'is_customer' => 1,
            'total_receipt_mail' => 0,
            'group_id' => $request->group_child,
            'group_parent_id' => $request->group_id,
            'job_id' => $request->job,
            'cus_source' => $request->list_type,
            'province_id' => $request->province,
            'district_id' => $request->district,
            'campaign_date_to' => 1,
            'is_birthday' => 0,
            'birthday' => strtotime($request->birthday),
            'created_from' => strtotime($request->created_at),
            'created_to' => strtotime($request->created_at) + 86399,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->id()
        ];

        $this->update_data_mail_campaign($request, $data, $type_send, $id);

        return redirect()->route('admin.contact.campaign.list');
    }

    //-----------------------------------------------------SUPPORT METHOD---------------------------------------------//
    # Get campaigns
    private function get_campaigns(Request $request): Builder
    {
        $listQuery = AdminMailCampaign::query()
            ->where('is_customer', 1)
            ->orderBy('admin_mail_campaign.id', 'desc')
            ->join('admin_mail_template', 'admin_mail_template.id', '=', 'admin_mail_campaign.mail_template_id');

        if ($request->request_list_scope == 2) { // nhóm của tôi
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listQuery = $listQuery
                ->join('admin', 'admin_mail_campaign.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->select('admin_mail_campaign.id', 'admin_mail_campaign.created_by', 'admin_mail_campaign.created_at', 'admin_mail_campaign.campaign_name', 'admin_mail_campaign.total_mail', 'admin_mail_campaign.total_receipt_mail', 'admin_mail_campaign.total_mail', 'admin_mail_campaign.start_date', 'admin_mail_campaign.created_by', 'admin_mail_campaign.is_birthday', 'admin_mail_template.template_title');
        } else if ($request->request_list_scope == 3) { //chỉ mình tôi
            //lấy id admin đang đăng nhập
            $admin_id = Auth::guard('admin')->id();
            $listQuery = $listQuery
                ->where('admin_mail_campaign.created_by', $admin_id)
                ->orderBy('admin_mail_campaign.id', 'desc')
                ->select('admin_mail_campaign.id', 'admin_mail_campaign.created_by', 'admin_mail_campaign.created_at', 'admin_mail_campaign.campaign_name', 'admin_mail_campaign.total_mail', 'admin_mail_campaign.total_receipt_mail', 'admin_mail_campaign.total_mail', 'admin_mail_campaign.start_date', 'admin_mail_campaign.created_by', 'admin_mail_campaign.is_birthday', 'admin_mail_template.template_title');
        }
        return $listQuery;
    }

    # validate mail campaign
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
        if ($request->is_birthday) {
            // Đến ngày sinh nhật thì gửi
            $type_send = "send_birthday";
            $request->validate([
                'campaign_name' => 'required|min:1|max:250',
                'mail_template_id' => 'required|integer',
            ], $translator);
        } else {
            //đặt thời gian gửi cụ thể
            $type_send = "send_set_time";
            $request->validate([
                'campaign_name' => 'required|min:1|max:250',
                'mail_template_id' => 'required|integer',
                'start_date' => 'required',
                'start_time_hour' => 'required|between:0,23',
                'start_time_min' => 'required|between:0,59',
            ], $translator);
        }
        return $type_send;
    }

    # Insert mail campagin
    private function insert_data_mail_campaign(Request $request, $data, $type_send): ?int
    {
        if ($type_send == "send_birthday") {
            $data['is_birthday'] = 1;
        }
        else if ($type_send == "send_set_time") {
            //ngày giờ đặt lịch lấy từ input chuyển sang unixtime
            $data['start_date'] = date(strtotime($request->start_date . " " . $request->start_time_hour . ":" . $request->start_time_min . ":" . "00"));
            $data['start_time'] = $request->start_time_hour * 60 + $request->start_time_min;
        }

        $config = AdminMailConfig::create($data);
        $insertGetId = $config->id;

        return $insertGetId;
    }

    # Update mail campaign
    private function update_data_mail_campaign(Request $request, $data, $type_send, $id_campaign){
        $mailConfig = AdminMailConfig::findOrFail($id_campaign);

        if ($type_send == "send_birthday") {
            $data['is_birthday'] = 1;
        } else if ($type_send == "send_set_time") {
            //ngày giờ đặt lịch lấy từ input chuyển sang unixtime
            $data['start_date'] = date(strtotime($request->start_date . " " . $request->start_time_hour . ":" . $request->start_time_min . ":" . "00"));
            $data['start_time'] = $request->start_time_hour * 60 + $request->start_time_min;
        }

        $mailConfig->update($data);
    }
}
