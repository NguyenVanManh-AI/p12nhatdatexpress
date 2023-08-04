<?php

namespace App\Http\Controllers\Admin\MailCampaign;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Mails\CreateTemplateRequest;
use App\Http\Requests\Admin\Mails\UpdateTemplateRequest;
use App\Models\AdminMailTemplate;
use App\Services\Admins\MailTemplateService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailTemplateCampaignController extends Controller
{
    private MailTemplateService $mailTemplateService;

    public function __construct()
    {
        $this->mailTemplateService = new MailTemplateService;
    }

    //hàm chỉnh sửa mail mẫu
    public function post_edit_mail_template(UpdateTemplateRequest $request, $id)
    {
        $template = $this->mailTemplateService->getPermissionQuery()
            ->findOrFail($id);
        //tạo mảng insert
        $data = [
            'template_title' => $request->template_title,
            'template_content' => $request->template_content,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->user()->id
        ];
        //insert dữ liệu vào bảng
        $template->update($data);

        // $data['id'] = $id;
        // Helper::create_admin_log(178, $data);

        Toastr::success('Sửa thành công');
        return redirect()->route('admin.email-campaign.list-template');
    }

    //hàm hiển thị giao diện sửa mẫu mail
    public function edit_mail_template($id)
    {
        $getTemplate = $this->mailTemplateService->getPermissionQuery()
            ->findOrFail($id);

        return view('Admin.MailCampaign.EditMailTemplate', ['getTemplate' => $getTemplate]);
    }

    //hàm thêm mẫu mail
    public function post_add_mail_template(CreateTemplateRequest $request)
    {
        //tạo mảng insert
        $data = [
            'template_title' => $request->template_title,
            'template_content' => $request->template_content,
            'show_order' => 0,
            'created_at' => time(),
            'created_by' => Auth::guard('admin')->user()->id
        ];
        AdminMailTemplate::create($data);

        // Helper::create_admin_log(177, $data);

        Toastr::success('Thêm thành công');
        return redirect()->route('admin.email-campaign.list-template');
    }

    //hàm hiển thị giao diện thêm mẫu mail
    public function add_mail_template()
    {
        return view('Admin.MailCampaign.AddMailTemplate');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        $this->mailTemplateService->getPermissionQuery()
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

        $this->mailTemplateService->getPermissionQuery()
            ->onlyIsDeleted()
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

        $this->mailTemplateService->getPermissionQuery()
            ->onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    //danh sách mẫu mail
    public function list_mail_template(Request $request)
    {
        //lấy ra 10 dòng
        $items = 10;
        //nếu có request từ url và items là số
        if ($request->has('items') && is_numeric($request->items)) {
            // lấy ra số dòng tương ứng
            $items = $request->items;
        }
        //phân quyền
        if ($request->request_list_scope == 2) { // nhóm của tôi
            //lấy role_id phân quyền
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            //select bảng admin_mail_template
            $listQuery = AdminMailTemplate::query()
                //nối với bảng admin
                ->join('admin', 'admin_mail_template.created_by', '=', 'admin.id')
                //điều kiện rol_id = admin_role_id
                ->where('admin.rol_id', $admin_role_id)
                //lấy ra id, created_by, template_title, created_at
                ->select('admin_mail_template.id', 'admin_mail_template.created_by', 'admin_mail_template.template_title', 'admin_mail_template.created_at');
        } else if ($request->request_list_scope == 3) { //chỉ mình tôi
            //lấy id admin đang đăng nhập
            $admin_id = Auth::guard('admin')->user()->id;
            //select bảng admin_mail_template
            $listQuery = AdminMailTemplate::query()
                //điều kiện created_by = id của admin đang đăng nhập
                ->where('admin_mail_template.created_by', $admin_id);
        } else { //tất cả
            //select bảng admin_mail_template
            $listQuery = AdminMailTemplate::query();
        }
        //lọc theo từ khóa
        if ($request->keyword) {
            //điều kiện từ khóa có trong template_title
            $listQuery->where('admin_mail_template.template_title', 'like', '%' . $request->keyword . '%');
        }
        //lọc theo từ ngày
        if ($request->from_date) {
            //điều kiện từ ngày nhỏ hơn created_at
            $listQuery->where('admin_mail_template.created_at', '>', date(strtotime($request->from_date)));
        }
        //lọc theo đến ngày
        if ($request->to_date) {
            //điều kiện đến ngày lớn hơn created_at
            $listQuery->where('admin_mail_template.created_at', '<', date(strtotime($request->to_date)));
        }
        //lấy ra danh sách và phân trang
        $list = $listQuery->where('is_system', 0)
            ->latest('admin_mail_template.show_order')
            ->latest('admin_mail_template.id')
            ->paginate($items);
        //đếm item đã xóa
        $trash_num = $this->mailTemplateService->getPermissionQuery($request)
            ->onlyIsDeleted()->count();

        //truyền list và trash_num sang view
        return view('Admin.MailCampaign.ListTemplate', compact('list', 'trash_num'));
    }

    //danh sách thùng rác
    public function trash(Request $request)
    {
        //lấy ra 10 dòng
        $items = 10;
        //nếu có request từ url và items là số
        if ($request->has('items') && is_numeric($request->items)) {
            // lấy ra số dòng tương ứng
            $items = $request->items;
        }
        //phân quyền
        if ($request->request_list_scope == 2) { // nhóm của tôi
            //lấy role_id phân quyền
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            //select bảng admin_mail_template
            $listQuery = AdminMailTemplate::onlyIsDeleted()
                //hiển thị theo thứ tự của show_order từ cao đến thấp
                ->orderBy('admin_mail_template.id', 'desc')
                //nối với bảng admin
                ->join('admin', 'admin_mail_template.created_by', '=', 'admin.id')
                //điều kiện rol_id = admin_role_id
                ->where('admin.rol_id', $admin_role_id)
                //lấy ra id, created_by, template_title, created_at
                ->select('admin_mail_template.id', 'admin_mail_template.created_by', 'admin_mail_template.template_title', 'admin_mail_template.created_at');
        } else if ($request->request_list_scope == 3) { //chỉ mình tôi
            //lấy id admin đang đăng nhập
            $admin_id = Auth::guard('admin')->user()->id;
            //select bảng admin_mail_template
            $listQuery = AdminMailTemplate::onlyIsDeleted()
                //điều kiện created_by = id của admin đang đăng nhập
                ->where('admin_mail_template.created_by', $admin_id)
                //hiển thị theo thứ tự của show_order từ cao đến thấp
                ->orderBy('admin_mail_template.id', 'desc');
        } else { //tất cả
            //select bảng admin_mail_template
            $listQuery = AdminMailTemplate::onlyIsDeleted()
                //hiển thị theo thứ tự của show_order từ cao đến thấp
                ->orderBy('admin_mail_template.id', 'desc');
        }
        //lọc theo từ khóa
        if ($request->keyword) {
            //điều kiện từ khóa có trong template_title
            $listQuery->where('admin_mail_template.template_title', 'like', '%' . $request->keyword . '%');
        }
        //lọc theo từ ngày
        if ($request->from_date) {
            //điều kiện từ ngày nhỏ hơn created_at
            $listQuery->where('admin_mail_template.created_at', '>', date(strtotime($request->from_date)));
        }
        //lọc theo đến ngày
        if ($request->to_date) {
            //điều kiện đến ngày lớn hơn created_at
            $listQuery->where('admin_mail_template.created_at', '<', date(strtotime($request->to_date)));
        }
        //lấy ra danh sách và phân trang
        $list = $listQuery->where('is_system', 0)->paginate($items);

        return view('Admin.MailCampaign.TrashListTemplate', compact('list'));
    }

    //hàm thay đổi thứ tự mẫu mail
    public function change_show_order(Request $request)
    {
        for ($i = 0; $i < count($request->select_item); $i++) {
            $value = $request->show_order[$request->select_item[$i]];

            // old should check and improve
            $this->mailTemplateService->getPermissionQuery()
                ->where('id', $request->select_item[$i])
                ->each(function ($template) use ($value) {
                    $template->update([
                        'show_order' => $value,
                        'updated_at' => time(),
                        'updated_by' => Auth::guard('admin')->user()->id
                    ]);
                });
        }

        Toastr::success("Thành công");
        return redirect()->back();
    }
}

