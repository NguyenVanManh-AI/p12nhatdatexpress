<?php
namespace App\Http\Controllers\Admin\SystemConfig;

use App\Enums\AdminActionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SystemConfig\CreateMailTemplateRequest;
use App\Http\Requests\Admin\SystemConfig\UpdateMailTemplateRequest;
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

    public function index(Request $request)
    {
        $templates = $this->mailTemplateService->index($request);

        $trashCountRequest = new Request([
            'request_list_scope' => $request->request_trash_list_scope
        ]);
        $trashCount = $this->mailTemplateService->index($trashCountRequest, 'only')->total();

        return view('admin.mails.templates.index', [
            'templates' => $templates,
            'countTrash' => $trashCount
        ]);
    }

    public function trash(Request $request)
    {
        $templates = $this->mailTemplateService->index($request, 'only');

        return view('admin.mails.templates.trash', [
            'templates' => $templates,
        ]);
    }

    public function create()
    {
        return view('admin.mails.templates.create');
    }

    public function store(CreateMailTemplateRequest $request)
    {
        $data = [
            'template_title' => $request->template_title,
            'template_mail_title' => $request->template_mail_title,
            'template_content' => $request->template_content,
            'template_action' => $request->template_action,
            'is_system' => 1,
            'created_at'=> time(),
            'created_by' => Auth::guard('admin')->user()->id,
        ];

        AdminMailTemplate::create($data);

        Toastr::success('Thêm thành công');
        return redirect(route('admin.templates.index'));
    }

    public function edit(Request $request, $id)
    {
        $template = $this->mailTemplateService
            ->getPermissionQuery($request)
            ->findOrFail($id);

        return view('Admin.SystemConfig.MailUpdate', [
            'template' => $template
        ]);
    }

    public function update(UpdateMailTemplateRequest $request, $id)
    {
        $template = $this->mailTemplateService
            ->getPermissionQuery($request)
            ->findOrFail($id);

        $template->update([
            'template_title' => $request->template_title,
            'template_mail_title' => $request->template_mail_title,
            'template_content' => $request->template_content,
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.templates.index'));
    }

    public function deleteMultiple(Request $request)
    {
        $result = $this->mailTemplateService->action($request, AdminActionEnum::DELETE);

        $result
            ? Toastr::success('Chuyển vào thùng rác thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $result = $this->mailTemplateService->action($request, AdminActionEnum::RESTORE);

        $result
            ? Toastr::success('Khôi phục thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $result = $this->mailTemplateService->action($request, AdminActionEnum::FORCE_DELETE);

        $result
            ? Toastr::success('Xóa thành công')
            : Toastr::error('Không đủ quyền');
        return back();
    }

    public function updateOrderMultiple(Request $request)
    {
        [$actionRequest, $ids, $trashed] = getActionsParams($request, AdminActionEnum::UPDATE);

        foreach ($ids as $id) {
            $order = $request->show_order[$id];

            $item = $this->mailTemplateService
                ->getPermissionQuery($actionRequest, $trashed)
                ->find($id);

            if ($item)
                $item->update([
                    'show_order' => $order,
                    'updated_at' => time(),
                    'updated_by' => Auth::guard('admin')->user()->id
                ]);
        }

        Toastr::success('Cập nhật thành công');
        return back();
    }
}
