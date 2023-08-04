<?php

namespace App\Http\Controllers\Admin\MailCampaign;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminMailCampaignDetail;
use App\Models\AdminMailConfig;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;

// use App\Jobs\SendEmailCampaignNow2;

class MailConfigCampaignController extends Controller
{
	//hàm kiểm tra mail cấu hình
	public function test_mail_config(Request $request){
        //validate mail cấu hình
		$this->validate_config($request);
		try{
            $this->send_mail($request);
            //hiển thị kết quả thành công qua ajax
            echo "<p class='text-success mb-0'>Gửi thử thành công! Bạn có thể thêm email này</p>";
		}
		catch (\Swift_TransportException $transportExp){
            //hiển thị kết quả qua ajax
            dd($transportExp);
			echo "<p class='text-danger mb-0'>Gửi thử thất bại! Vui lòng kiểm tra lại thông tin cấu hình</p>";
		}
	}
	//hàm thêm mẫu mail
	public function post_add_mail_config(Request $request){
		$this->validate_config($request);
		//gửi thử mail
		try{
            //gửi mail
            $this->send_mail($request);

            //set data để insert
            $data = [
                'mail_host' => $request->mail_host,
                'mail_port' => $request->mail_port,
                'mail_username' => $request->mail_username,
                'mail_password' => $request->mail_password,
                'mail_encryption' => $request->mail_encryption,
                'is_deleted' => 0,
                'is_config' => $request->is_config ?? 0,
                'created_at' => time(),
                'created_by'=>Auth::guard('admin')->id()
            ];
            //insert vào bảng admin_mail_config
            AdminMailConfig::create($data);
            // Helper::create_admin_log(182,$data);
            session()->put('themthanhcong', 'true');
            //thông báo thành công
            echo "Thêm thành công";
		}
		catch (Swift_TransportException $transportExp){
            //hiển thị kết quả qua ajax
            dd($transportExp);
			echo "<p class='text-danger mb-0'>Thêm thất bại! Vui lòng kiểm tra lại thông tin cấu hình</p>";
		}
	}
	//hàm hiển thị giao diện thêm mẫu mail
	public function add_mail_config(){
		return view('Admin.MailCampaign.AddMailConfig');
	}

	public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        AdminMailConfig::query()
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

        AdminMailConfig::onlyIsDeleted()
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

		AdminMailConfig::onlyIsDeleted()
			->find($ids)
			->each(function($item) {
				$item->forceDelete();
				// should create log force delete
			});

		Toastr::success('Xóa thành công');
		return back();
	}


   	//danh sách mail cấu hình
	public function list_mail_config(Request $request){
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
    	//select bảng admin_mail_template
    	$listQuery = AdminMailConfig::query()
    	//hiển thị theo thứ tự của show_order từ cao đến thấp
    	->orderBy('admin_mail_config.id','desc')
    	//nối với bảng admin
    	->join('admin', 'admin_mail_config.created_by', '=', 'admin.id')
    	//điều kiện rol_id = admin_role_id
    	->where('admin.rol_id', $admin_role_id)
    	//lấy ra id, created_by, template_title, created_at
    	->select('admin_mail_config.id','admin_mail_config.created_by','admin_mail_config.mail_username');
        }else if ($request->request_list_scope == 3) { //chỉ mình tôi
        	//lấy id admin đang đăng nhập
        	$admin_id = Auth::guard('admin')->user()->id;
        	//select bảng admin_mail_template
        	$listQuery = AdminMailConfig::query()
        	//điều kiện created_by = id của admin đang đăng nhập
        	->where('admin_mail_config.created_by',$admin_id)
        	//hiển thị theo thứ tự của show_order từ cao đến thấp
        	->orderBy('admin_mail_config.id','desc');
        }else { //tất cả
        	//select bảng admin_mail_template
        	$listQuery = AdminMailConfig::query()
        	//hiển thị theo thứ tự của show_order từ cao đến thấp
        	->orderBy('admin_mail_config.id','desc');
        }
     	//lọc theo từ khóa
        if($request->keyword){
        	//điều kiện từ khóa có trong template_title
        	$listQuery->where('admin_mail_config.mail_username', 'like', '%' . $request->keyword. '%');
        }
        //lọc theo từ ngày
        if($request->from_date){
        	//điều kiện từ ngày nhỏ hơn created_at
        	$listQuery->where('admin_mail_config.created_at','>',date(strtotime($request->from_date)));
        }
         //lọc theo đến ngày
        if($request->to_date){
        	//điều kiện đến ngày lớn hơn created_at
        	$listQuery->where('admin_mail_config.created_at','<',date(strtotime($request->to_date)));
        }
        //lấy ra danh sách và phân trang
        $list = $listQuery->where('is_config', 0)->paginate($items);

       	$getCountSend = AdminMailCampaignDetail::query()
       	->join('admin_mail_campaign','admin_mail_campaign.id','admin_mail_campaign_detail.admin_campaign_id')
        ->where('admin_mail_campaign_detail.receipt_status','=',1)
       	->select('admin_mail_campaign_detail.admin_mail_config_id')
       	->get();
        //đếm item đã xóa
        $trash_num = AdminMailConfig::onlyIsDeleted()->count();
        //truyền list và trash_num sang view
        return view('Admin.MailCampaign.ListMailConfig', ['list'=>$list, 'trash_num'=>$trash_num,'getCountSend'=>$getCountSend]);
    }
    //thùng rác danh sách mail cấu hình
    public function trash(Request $request){
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
    	//select bảng admin_mail_template
    	$listQuery = AdminMailConfig::query()
    	//hiển thị theo thứ tự của show_order từ cao đến thấp
    	->orderBy('admin_mail_config.id','desc')
    	//nối với bảng admin
    	->join('admin', 'admin_mail_config.created_by', '=', 'admin.id')
    	//điều kiện rol_id = admin_role_id
    	->where('admin.rol_id', $admin_role_id)
    	//lấy ra id, created_by, template_title, created_at
    	->select('admin_mail_config.id','admin_mail_config.created_by','admin_mail_config.mail_username');
        }else if ($request->request_list_scope == 3) { //chỉ mình tôi
        	//lấy id admin đang đăng nhập
        	$admin_id = Auth::guard('admin')->user()->id;
        	//select bảng admin_mail_template
        	$listQuery = AdminMailConfig::query()
        	//điều kiện created_by = id của admin đang đăng nhập
        	->where('admin_mail_config.created_by',$admin_id)
        	//hiển thị theo thứ tự của show_order từ cao đến thấp
        	->orderBy('admin_mail_config.id','desc');
        }else { //tất cả
        	//select bảng admin_mail_template
        	$listQuery = AdminMailConfig::query()
        	//hiển thị theo thứ tự của show_order từ cao đến thấp
        	->orderBy('admin_mail_config.id','desc');
        }
     	//lọc theo từ khóa
        if($request->keyword){
        	//điều kiện từ khóa có trong template_title
        	$listQuery->where('admin_mail_config.mail_username', 'like', '%' . $request->keyword. '%');
        }
        //lọc theo từ ngày
        if($request->from_date){
        	//điều kiện từ ngày nhỏ hơn created_at
        	$listQuery->where('admin_mail_config.created_at','>',date(strtotime($request->from_date)));
        }
         //lọc theo đến ngày
        if($request->to_date){
        	//điều kiện đến ngày lớn hơn created_at
        	$listQuery->where('admin_mail_config.created_at','<',date(strtotime($request->to_date)));
        }
        //lấy ra danh sách và phân trang
        $list = $listQuery->onlyIsDeleted()->paginate($items);

       	$getCountSend = AdminMailCampaignDetail::query()
       	->join('admin_mail_campaign','admin_mail_campaign.id','admin_mail_campaign_detail.admin_campaign_id')
       	->select('admin_mail_campaign_detail.admin_mail_config_id')
       	->get();

        return view('Admin.MailCampaign.TrashListMailConfig', [
			'list' => $list,
			'getCountSend' => $getCountSend
		]);
    }
	//giao diện sửa mail cấu hình
	public function edit_mail_config($id){
		$mailconfig = AdminMailConfig::find($id);
		return view('Admin.MailCampaign.EditMailConfig',['mailconfig'=>$mailconfig]);
	}
    //sửa mail cấu hình
	public function post_edit_mail_config(Request $request, $id){
		$config = AdminMailConfig::findOrFail($id);

		$this->validate_config($request);
		//gửi thử mail
		try{
			$this->send_mail($request);
            session()->put('suathanhcong', 'true');
            echo "Thêm thành công";
		}
		catch (Swift_TransportException $transportExp){
			echo "<p class='text-danger mb-0'>Gửi thử thất bại! Vui lòng kiểm tra lại thông tin cấu hình</p>";
		}
			$data = [
				'mail_host' => $request->mail_host,
				'mail_port' => $request->mail_port,
				'mail_username' => $request->mail_username,
				'mail_password' => $request->mail_password,
				'mail_encryption' => $request->mail_encryption,
				'is_deleted' => 0,
				'updated_at' => time(),
				'updated_by'=>Auth::guard('admin')->id()
			];

		$config->update($data);

		Toastr::success('Gửi thành công');
		return back();
            // $data['id']=$id;
            // Helper::create_admin_log(185,$data);
	}

    # Send mail
    private function send_mail(Request $request){
        $transport = new Swift_SmtpTransport($request->mail_host, $request->mail_port, $request->mail_encryption);
        $transport->setUsername($request->mail_username)->setPassword($request->mail_password);

        $message = new Swift_Message("Test mail");
        $message->setFrom([$request->mail_username => "Test"])
            ->setTo($request->mail_username)
            ->setBody("Test mail", 'text/html');

        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
    }

    # Validate config
    private function validate_config(Request $request){
        $request->validate([
            'mail_host' => 'required|min:1|max:250|',
            'mail_port' => 'required|integer|between:0,100000',
            'mail_username' => 'required|min:1|max:250',
            'mail_password' => 'required|min:6|max:250',
        ]);
    }
}

