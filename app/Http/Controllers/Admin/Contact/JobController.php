<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Contact\AddJobRequest;
use App\Http\Requests\Admin\Contact\UpdateJobRequest;
use App\Models\User\CustomerParam;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    private const TABLE = 'customer_param';
    private const LOG = [
        'CREATE' => 198,
        'UPDATE' => 199,
        'SOFT_REMOVE' => 200,
        'REMOVE' => 201,
        'RESTORE' => 202,
    ];
    # Danh sách nghành nghề
    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }
        $query = $this->get_jobs($request);

        $list = $query->latest('id')->paginate($items);
        $count_trash = $this->get_jobs($request)->onlyIsDeleted()->count();

        return view('Admin.Contact.Job.ListJob', compact('list', 'count_trash'));

    }

    # Danh sách thùng rác nghành nghề
    public function trash(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }
        $query = $this->get_jobs($request);

        $list = $query->onlyIsDeleted()->latest('id')->paginate($items);

        return view('Admin.Contact.Job.TrashJob', compact('list'));
    }


    # Add
    public function store(AddJobRequest $request): RedirectResponse
    {
        $lasted_code = CustomerParam::where('param_type', 'JB')->select('param_code')->latest()->first()->param_code;
        $data = [
            'param_type' => 'JB',
            'param_code' => $lasted_code + 1,
            'param_name' => $request->job_name,
            'created_at' => time(),
            'created_by' => Auth::guard('admin')->id()
        ];
        // Helper::create_admin_log(self::LOG['CREATE'], $data);
        CustomerParam::create($data);

        Toastr::success('Thêm thành công');
        return back();
    }

    # update
    public function update(UpdateJobRequest $request, $id): RedirectResponse
    {
        $param = CustomerParam::findOrFail($id);
        $data = [
            'param_name' => $request->job_name,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->id()
        ];
        $param->update($data);
        // Helper::create_admin_log(self::LOG['UPDATE'], $data);

        Toastr::success('Sửa thành công');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        CustomerParam::query()
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

        CustomerParam::onlyIsDeleted()
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

        CustomerParam::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    //------------------------------------------------SUPPORT METHOD--------------------------------------------------//
    private function get_jobs(Request $request): Builder
    {
        $query = CustomerParam::query()
            ->where('param_type', 'JB')
            ->join('admin', self::TABLE . '.created_by', '=', 'admin.id')
            ->select(self::TABLE . '.id', self::TABLE . '.param_name', 'admin.admin_fullname', self::TABLE . '.created_at', self::TABLE . '.is_deleted', self::TABLE . '.created_by');

        // check group
        if ($request->request_list_scope == 2) {
            $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
            $query->where('admin.rol_id', $admin_role_id);

        } //check self
        else if ($request->request_list_scope == 3) {
            $admin_id = Auth::guard('admin')->id();
            $query->where([self::TABLE . '.created_by' => $admin_id]);
        } //check all

        if ($request->keyword) {
            $query->where('param_name', 'like', "%$request->keyword%");
        }

        return $query;
    }
}
