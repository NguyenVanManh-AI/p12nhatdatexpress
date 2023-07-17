<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerGroupRequest;
use App\Models\Banner\BannerGroup;
use App\Traits\Filterable;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannerGroupController extends Controller
{
    use Filterable;

    // Array filterable
    protected $filterable = [
        'keyword' => 'banner_name',
        'banner_group',
        'banner_position',
        'banner_type',
    ];
    protected $table = 'banner_group';

    /** Filter keyword
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterKeyword($query, $value)
    {
        return $query->where($this->table . '.' . 'banner_name', 'like', "%$value%");
    }

    //------------------------------------------------------------------------LIST------------------------------------------------------------------------//

    /** List
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items))
            $items = $request->items;

        $list = BannerGroup::query();

        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $list = $list->select($this->table . '.*', 'admin.rol_id')
                    ->join('admin', $this->table . '.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $list = $list->where([ $this->table . '.created_by' => $admin_id]);
            }
        }

        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $list = $this->scopeFilter($list, $params);

        $list = $list->orderBy('id', 'desc')->paginate($items);
        return view('Admin.Banner.ListBannerGroup', compact('list'));
    }

    //---UPDATE---//
    /** Edit
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit($id)
    {
        $item = BannerGroup::findOrFail($id);

        return view('Admin.Banner.EditBannerGroup', compact('item'));
    }

    /** Update
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(BannerGroupRequest $request, int $id)
    {
        $bannerGroup = BannerGroup::findOrFail($id);

        $bannerGroup->update([
            'banner_group' => $request->banner_group,
            'banner_group_name' => $request->banner_group_name,
            'banner_permission' => $request->banner_permission ?? 0,
            'banner_type' => $request->banner_type,
            'banner_name' => $request->banner_name,
            'banner_position' => $request->banner_position,
            'banner_description' => $request->banner_description,
            'banner_width' => $request->banner_width,
            'banner_height' => $request->banner_height,
            'banner_coin_price' => $request->banner_coin_price,
            'banner_price' => $request->banner_price,
            'updated_by' => Auth::guard('admin')->id(),
            'updated_at' => time(),
        ]);
        // Helper::create_admin_log(67,$data);

        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.banner.locate.list'));
    }

    /** Restore
     * @param $ids
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        foreach ($ids as $id) {
            $bannerGroup = BannerGroup::onlyIsDeleted()->find($id);
            if (!$bannerGroup) continue;

            $bannerGroup->restore();
            // Helper::create_admin_log(69, $item);
        }

        // Notify
        Toastr::success('Khôi phục thành công!');
        return redirect()->back();
    }

    //------------------------------------------------------------------------DELETE------------------------------------------------------------------------//
    /** Delete
     * @param $ids
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        foreach ($ids as $id) {
            $bannerGroup = BannerGroup::find($id);
            if (!$bannerGroup) continue;

            $bannerGroup->delete();
            // Helper::create_admin_log(68, $item);
        }
        // Notify
        Toastr::success('Xóa thành công');
        return redirect()->back();
    }

    /** Force delete
     * @param $ids
     * @return \Illuminate\Http\RedirectResponse
     */
    public function force_delete($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);

        foreach ($ids as $id) {
            $bannerGroup = BannerGroup::onlyIsDeleted()->find($id);
            if (!$bannerGroup) continue;

            $bannerGroup->forceDelete();
            // Helper::create_admin_log(70, $item);
        }

        // Notify
        Toastr::success('Xóa thành công');
        return back();
    }

    //------------------------------------------------------------------------ACTION---------------------------------------------------------------------//

    /** Action in table
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
        }
        // Action Duplicate
        else if ($request->query('action') == "duplicate") {
            $this->duplicate(array_values($request->select_item));
        }
        // Action Force Delete
        else if ($request->query('action') == "delete") {
            $this->force_delete(array_values($request->select_item));
        }
        // Action Restore
        else if ($request->query('action') == "restore") {
            $this->restore(array_values($request->select_item));
        }
        // Update banner permission
        else if ($request->query('action') == "update") {
            for ($i = 0; $i < count($request->select_item); $i++) {
                $value = $request->banner_permission[$request->select_item[$i]] ?? 0;

                $bannerGroup = BannerGroup::find($request->select_item[$i]);
                if (!$bannerGroup) continue;

                $bannerGroup->update([
                    'banner_permission' => $value,
                    'updated_at' => time(),
                    'updated_by' => Auth::guard('admin')->user()->id
                ]);
            }

            // Notify
            Toastr::success("Thành công");
        }

        return redirect()->back();
    }
}
