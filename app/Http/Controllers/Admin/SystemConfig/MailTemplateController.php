<?php
namespace App\Http\Controllers\Admin\SystemConfig;

use App\Http\Controllers\Controller;
use App\Models\AdminMailTemplate;
use App\Services\Admins\MailTemplateService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailTemplateController extends Controller
{
    private MailTemplateService $mailTemplateService;

    public function __construct()
    {
        $this->mailTemplateService = new MailTemplateService;
    }

    //danh sách mail
    public function index(Request $request)
    {
        $request['admin_rol_id'] = Auth::guard('admin')->user()->rol_id;
        $request['admin_id'] = Auth::guard('admin')->user()->id;
        $templates = $this->mailTemplateService->index($request->all());

        return view('admin.mails.templates.index', [
            'templates' => $templates,
        ]);
    }

    public function list_trash_mail(Request $request)
    {

        $items = 10;
        if ($request->has('items')) {
            $items = $request->items;
        }
        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;

            $mailtemplate = AdminMailTemplate::onlyIsDeleted()
                ->select('admin_mail_template.id',
                    'template_title','template_mail_title','template_action','admin_mail_template.created_by','admin_mail_template.show_order')
                ->whereNotNull('template_action')
                ->where('is_system', 1)
                ->join('admin', 'admin_mail_template.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->orderBy('admin_mail_template.show_order', 'DESC')
                ->orderBy('admin_mail_template.id', 'DESC')
                ->paginate($items);
        }
        else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;
            $mailtemplate = AdminMailTemplate::onlyIsDeleted()
                ->whereNotNull('template_action')
                ->where('is_system', 1)
                ->where(['created_by' => $admin_id])
                ->orderBy('show_order', 'DESC')
                ->orderBy('id', 'DESC')
                ->paginate($items);
        }
        else { // all || check
            $mailtemplate = AdminMailTemplate::onlyIsDeleted()
                ->orderBy('show_order', 'DESC')
                ->where('is_system', 1)
                ->whereNotNull('template_action')
                ->orderBy('id', 'DESC')
                ->paginate($items);
        }

        //lấy số lượng item chưa xóa
        $count_item = AdminMailTemplate::system()->where('is_deleted', 0)->count();

        return view('Admin.SystemConfig.MailTrash', compact('mailtemplate', 'count_item'));
    }

    public function mailtemplate_new()
    {
        return view('Admin.SystemConfig.MailNew');
    }

    public function post_mailtemplate_new(Request $request)
    {

        $validate = $request->validate([
            'template_title' => 'required|unique:admin_mail_template,template_title',
            'template_action' => 'required|unique:admin_mail_template,template_action',
            'template_content' => 'required'
        ],
            [
                'template_title.required' => 'Vui lòng nhập tiêu đề',
                'template_title.unique' =>'Tiêu đề mail đã tồn tại',
                'template_action.required' => 'Vui lòng nhập action',
                'template_action.unique' => 'Action đã tồn tại',
                'template_content.required' => 'Vui lòng nhập nội dung',
            ]
        );
        $data = [
            'template_title' => $request->template_title,
            'template_mail_title' => $request->template_mail_title,
            'template_content' => $request->template_content,
            'template_action' => $request->template_action,
            'is_system' => 1,
            'created_at'=> time(),
            'created_by'=>Auth::guard('admin')->user()->id,
        ];

        AdminMailTemplate::create($data);

        Toastr::success('Thêm thành công');
        return redirect(route('admin.templates.index'));
    }

    public function edit(AdminMailTemplate $template)
    {
        return view('Admin.SystemConfig.MailUpdate', [
            'template' => $template
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
                'template_title'=>'required',
                'template_content' => 'required'
        ],
            [
                'template_title.required' => 'Vui lòng nhập tiêu đề',
                'template_content.required' => 'Vui lòng nhập nội dung',
            ]
        );

        // should check permission not find id = $id
        $template = AdminMailTemplate::find($id);

        $template->update([
            'template_title' => $request->template_title,
            'template_mail_title' => $request->template_mail_title,
            'template_content' => $request->template_content,
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);
        // $data=[
        //     'template_title' => $request->template_title,
        //     'template_mail_title' => $request->template_mail_title,
        //     'template_content' => $request->template_content,
        //     'updated_at' => time(),
        //     'updated_by'=>Auth::guard('admin')->user()->id,
        // ];
        # Note log
        // $data['id'] = $id;
        // Helper::create_admin_log(57, $data);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.templates.index'));
    }

    // function chuyển item vào thùng rác
    public function trash_item($id)
    {
        $mail = AdminMailTemplate::system()->findOrFail($id);

        // nằm item ở list thì sẽ chuyển vào thùng rác , ngược lại sẽ khôi phục
        $mail->deleted();

        // # Note log
        // Helper::create_admin_log(58, [
        //     'id'=>$id,
        //     'is_deleted' => 1
        // ]);

        Toastr::success('Xóa thành công');
        return back();
    }
    public function untrash_item($id){
        $mail = AdminMailTemplate::system()->findOrFail($id);

        $mail->restore();

        // # Note log
        // Helper::create_admin_log(59, [
        //     'id'=>$id,
        //     'is_deleted' => 0
        // ]);

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function mail_update_order(Request $request, $id)
    {
        $mail = AdminMailTemplate::system()->findOrFail($id);

        $mail->update([
            'show_order' => $request->order
        ]);

        Toastr::success('Cập nhật thành công');
        return back();
    }

    public function trash_listitem(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        if ($request->action == "trash") {
            for ($i = 0; $i < count($request->select_item); $i++) {
                $mail = AdminMailTemplate::system()->find($request->select_item[$i]);

                if (!$mail) continue;
                $mail->delete();
            }
        }
        if ($request->action == "restore") {
            for ($i = 0; $i < count($request->select_item); $i++) {
                $mail = AdminMailTemplate::system()->find($request->select_item[$i]);

                if (!$mail) continue;
                $mail->restore();
            }
        }
        if ($request->action == "update") {
            for ($i = 0; $i < count($request->select_item); $i++) {
                $vt = "order" . (string)$request->select_item[$i];

                $mail = AdminMailTemplate::system()->find($request->select_item[$i]);
                if (!$mail) continue;

                $mail->update([
                    'show_order' => $request->$vt
                ]);
            }
        }
        Toastr::success("Thành công");
        return back();
    }
    public function delete_item($id){
        $mail = AdminMailTemplate::system()->findOrFail($id);
        // Helper::create_admin_log(60,$mail );
        $mail->delete();

        Toastr::success('Xóa thành công');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        AdminMailTemplate::onlyIsDeleted()
            ->find($ids)
            ->system()
            ->each(function($template) {
                $template->delete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        AdminMailTemplate::onlyIsDeleted()
            ->system()
            ->find($ids)
            ->each(function($template) {
                $template->restore();
            });

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        // xóa hẳn onlyIsDeleted() nếu muốn check đã xóa
        AdminMailTemplate::withIsDeleted()
            ->system()
            ->find($ids)
            ->each(function($template) {
                $template->forceDelete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }
}
