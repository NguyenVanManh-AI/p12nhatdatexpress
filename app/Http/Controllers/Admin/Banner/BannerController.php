<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerRequest;
use App\Models\Banner\Banner;
use App\Models\Banner\BannerGroup;
use App\Models\Group;
use App\Traits\Filterable;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    use Filterable;

    // Array filterable
    protected $filterable = [

    ];
    protected $table = 'banner';

    /** Filter keyword
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterKeyword($query, $value)
    {
        return $query->where($this->table . '.' . 'banner_title', 'like', "%$value%");
    }

    //------------------------------------------------------------------------LIST------------------------------------------------------------------------//

    /** List
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        // Retrieve data
        $group_banner = BannerGroup::get();
        $list = Banner::select(['banner.*', 'g.group_name', 'g.id as group_id'])
            ->leftJoin('group as g', 'banner.group_id', '=', 'g.id');

        $trash_num = Banner::onlyIsDeleted()->count('id');
        // default banner group loaded
        if ($request->has('banner_group_id') && is_numeric($request->banner_group_id))
            $list->where('banner_group_id', $request->banner_group_id);
        else
            $list->where('banner_group_id', $group_banner[0]->id);

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $list = $list->select($this->table . '.*', 'admin.rol_id')
                    ->join('admin', $this->table . '.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $list = $list->where([$this->table . '.created_by' => $admin_id]);
            }
        }

        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $list = $this->scopeFilter($list, $params);

        $list = $list->orderBy($this->table . '.id', 'desc')->paginate($items);
        return view('Admin.Banner.ListBanner', compact('list', 'trash_num', 'group_banner'));
    }

    /** List Trash
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list_trash(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        // Retrieve data
        $group_banner = BannerGroup::get();
        $list = Banner::select(['banner.*', 'g.group_name', 'g.id as group_id'])
            ->leftJoin('group as g', 'banner.group_id', '=', 'g.id')
            ->onlyIsDeleted();

        // default banner group loaded
        if ($request->has('banner_group_id') && is_numeric($request->banner_group_id))
            $list->where('banner_group_id', $request->banner_group_id);
        else
            $list->where('banner_group_id', $group_banner[0]->id);

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $list = $list->select($this->table . '.*', 'admin.rol_id')
                    ->join('admin', $this->table . '.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $list = $list->where([$this->table . '.created_by' => $admin_id]);
            }
        }

        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $list = $this->scopeFilter($list, $params);

        $list = $list->orderBy($this->table . '.id', 'desc')->paginate($items);
        return view('Admin.Banner.ListTrashBanner', compact('list', 'group_banner'));
    }

    //------------------------------------------------------------------------CREATE------------------------------------------------------------------------//

    /** Add
     * @param Request $request
     * @return Application|Factory|View
     */
    public function add(Request $request)
    {
        $group = Group::get();
        $banner_group = BannerGroup::get();

        // Return view to add
        return view('Admin.Banner.AddBanner', compact('group', 'banner_group'));
    }

    /** Store
     * @param BannerRequest $request
     * @return RedirectResponse
     */
    public function store(BannerRequest $request): RedirectResponse
    {
        $data = [
            'banner_group_id' => $request->banner_group_id,
            'group_id' => $request->group_id,
            'banner_title' => $request->banner_title,
            'image_url' => $request->image_url,
            'link' => $request->link,
            'target_type' => $request->target_type,
            'banner_code' => $request->banner_code,
            'banner_default' => 1,
            'created_by' => Auth::guard('admin')->id(),
            'created_at' => strtotime('now')
        ];
        // Check date
        if ($request->date) {
            $date_array = explode('-', $request->date);
            $data['date_from'] = strtotime(trim(str_replace('/', '-', $date_array[0])));
            $data['date_to'] = strtotime(trim(str_replace('/', '-', $date_array[1])));
        }
        // Add to database
        Banner::create($data);
        // Helper::create_admin_log(71, $data);
        // Notify
        Toastr::success('Thêm thành công');
        return redirect()->route('admin.banner.list');
    }

    //------------------------------------------------------------------------UPDATE------------------------------------------------------------------------//

    /** Edit
     * @param Banner $banner
     * @return Application|Factory|View|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit(Banner $banner)
    {
        // Retrieve group
        $group = Group::get();

        // Retrieve banner group
        $banner_group = BannerGroup::get();

        return view('Admin.Banner.EditBanner', [
            'item' => $banner,
            'group' => $group,
            'banner_group' => $banner_group,
        ]);
    }

    /** Update
     * @param Request $request
     * @param int $id
     * @return Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(BannerRequest $request, int $id)
    {
        $data = [
            'banner_group_id' => $request->banner_group_id,
            'group_id' => $request->group_id,
            'banner_title' => $request->banner_title,
            'image_url' => $request->image_url,
            'link' => $request->link,
            'target_type' => $request->target_type,
            'banner_code' => $request->banner_code,
            'banner_default' => 1,
            'updated_by' => Auth::guard('admin')->id(),
            'updated_at' => strtotime('now'),
            'date_from' => null,
            'date_to' => null,
        ];

        $banner = Banner::findOrFail($id);

        // Check date
        if ($request->date) {
            $date_array = explode('-', $request->date);
            $data['date_from'] = strtotime(Carbon::createFromFormat('d-m-Y g:ia', trim(str_replace('/', '-', $date_array[0])))->toDateTimeString());
            $data['date_to'] = strtotime(Carbon::createFromFormat('d-m-Y g:ia', trim(str_replace('/', '-', $date_array[1])))->toDateTimeString());
        }

        $banner->update($data);
        // $data['id'] = $id;
        // Helper::create_admin_log(72, $data);

        // Notify
        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.banner.list'));
    }

    /** Restore
     * @param $ids
     * @return RedirectResponse
     */
    public function restore($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        foreach ($ids as $id) {
            $banner = Banner::onlyIsDeleted()->find($id);
            if (!$banner) continue;

            $banner->restore();
            // Helper::create_admin_log(74, $item);
        }

        // Notify
        Toastr::success('Khôi phục thành công!');
        return redirect()->back();
    }

    //------------------------------------------------------------------------DELETE------------------------------------------------------------------------//

    /** Delete
     * @param $ids
     * @return RedirectResponse
     */
    public function delete($ids)
    {
        // Convert array
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        // Loop
        foreach ($ids as $id) {
            $banner = Banner::find($id);
            if (!$banner) continue;

            $banner->delete();
            // Helper::create_admin_log(73, ['id' => $id, 'is_deleted' => 1, 'updated_at' => strtotime('now'), 'updated_by' => Auth::guard('admin')->user()->id]);
        }

        // Notify
        Toastr::success('Xóa thành công');
        return redirect()->back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Banner::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                // maybe should remove user upload file not admin image
                // if ($item->image_url && str_starts_with($item->image_url, '/uploads/users')) {
                //     removeFile($item->image_url);
                // }

                $item->forceDelete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    /** Force delete
     * @param $ids
     * @return RedirectResponse
     */
    public function force_delete($ids)
    {
        // Convert array
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        // Loop
        foreach ($ids as $id) {
            $banner = Banner::onlyIsDeleted()->find($id);
            if (!$banner) continue;

            $banner->forceDelete();
        }

        // Notify
        Toastr::success('Xóa thành công');
        return back();
    }

    //------------------------------------------------------------------------ACTION---------------------------------------------------------------------//

    /** Action in table
     * @param Request $request
     * @return RedirectResponse
     */
    public function action(Request $request)
    {
        // Check selected is not empty
        if ($request->select_item == null || !is_array($request->select_item)) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        // Action Delete
        if ($request->query('action') == "trash") {
            $this->delete(array_values($request->select_item));
        } // Action Duplicate
        else if ($request->query('action') == "duplicate") {
            $this->duplicate(array_values($request->select_item));
        } // Action Force Delete
        else if ($request->query('action') == "delete") {
            $this->force_delete(array_values($request->select_item));
        } // Action Restore
        else if ($request->query('action') == "restore") {
            $this->restore(array_values($request->select_item));
        } // Update show order
        else if ($request->query('action') == "update") {
            for ($i = 0; $i < count($request->select_item); $i++) {
                $value = $request->show_order[$request->select_item[$i]];
//                $highlight = $request->is_highlight[$request->select_item[$i]];
                $banner = Banner::find($request->select_item[$i]);
                if (!$banner) continue;

                $banner->update([
                    'show_order' => $value,
                    'updated_at' => time(),
                    'updated_by' => Auth::guard('admin')->user()->id
                ]);
            }

            // Notify
            Toastr::success("Thành công");
        }

        return redirect()->back();
    }

//------------------------------------------------------------------------AJAX---------------------------------------------------------------------//

    public function get_group($id_banner_group)
    {
        $banner_group = BannerGroup::find($id_banner_group);

        if ($banner_group) {
            $is_home = $banner_group->banner_group == 'H' ? 1 : 0;
            $group = Group::query();
            if ($is_home)
                $group->where('id', 1);
            else
                $group->where('id', '<>', 1);

            return response()->json(['group' => $group->get(), 'status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'fail'], 200);
        }
    }

}

