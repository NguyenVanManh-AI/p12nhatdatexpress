<?php

namespace App\Http\Controllers\Admin\Guide;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\UserGuide;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use File;

class GuideController extends Controller
{
    public function list_guide(Request $request)
    {
        //lấy data phân trang
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }
        //check group
        if ($request->request_list_scope == 2) {
            $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
            $list_Guide = UserGuide::query()
                ->join('admin', 'user_guide.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->select('user_guide.id', 'user_guide.image_url', 'user_guide.guide_title', 'admin.admin_fullname', 'user_guide.guide_content', 'user_guide.created_by', 'user_guide.created_at', 'user_guide.is_deleted');
        } //check self
        else if ($request->request_list_scope == 3) {
            $admin_id = Auth::guard('admin')->user()->id;
            $list_Guide = UserGuide::query()
                ->leftJoin('admin', 'user_guide.created_by', '=', 'admin.id')
                ->select('user_guide.id', 'user_guide.image_url', 'user_guide.guide_title', 'admin.admin_fullname', 'user_guide.guide_content', 'user_guide.created_by', 'user_guide.created_at', 'user_guide.is_deleted')
                ->where(['user_guide.created_by' => $admin_id]);
        } //check all
        else {
            $list_Guide = UserGuide::query()
                ->leftJoin('admin', 'user_guide.created_by', '=', 'admin.id')
                ->select('user_guide.id', 'user_guide.image_url', 'user_guide.guide_title', 'admin.admin_fullname', 'user_guide.guide_content', 'user_guide.created_by', 'user_guide.created_at', 'user_guide.is_deleted');
        }


        // Tìm kiếm
        if ($request->has('keyword') && $request->keyword != '') {
            $list_Guide->where('guide_title', 'LIKE', '%' . $request->keyword . '%');
        }
        if ($request->has('author') && $request->author != '') {
            $list_Guide->where('user_guide.created_by', $request->author);
        }
        if (($request->has('date_start') && $request->date_start != '') || ($request->has('date_end') && $request->date_end != '')) {
            if ($request->date_start == '') {
                $start = Carbon::parse($request->date_end);
                $end = Carbon::parse($request->date_end)->addDay(1);
            } else if ($request->date_end == '') {
                $start = Carbon::parse($request->date_start);
                $end = Carbon::parse($request->date_start)->addDay(1);
            } else {
                $start = Carbon::parse($request->date_start);
                $end = Carbon::parse($request->date_end)->addDay(1);
            }
            $start = strtotime($start);
            $end = strtotime($end);
            $list_Guide->whereBetween('user_guide.created_at', [$start, $end]);
        }
        //data
        $auth = Admin::select('id', 'admin_fullname')->get();
        $list_Guide = $list_Guide->orderBy('id', 'desc')->paginate($items);
        $count_trash = UserGuide::onlyIsDeleted()->count();
        return view('Admin/Guide/ListGuide', compact('list_Guide', 'count_trash', 'auth'));

    }

    public function add(Request $request)
    {

        return view('Admin.Guide.AddGuide');
    }

    public function postguide(Request $request)
    {

        $validate = $request->validate([
            'guide_title' => 'required',
            // 'guide_url'=> 'required',
            'file' => 'required|image',
            'guide_content' => 'required',
        ], [

            'guide_title.required' => '*Vui lòng nhập tiêu đề',
            'file.required' => '*Vui lòng chọn ảnh',
            'file.image' => '* Vui lòng chọn ảnh',
            'guide_content.required' => '*Vui lòng thêm nội dung',
        ]);

        $data = [
            'guide_title' => $request->guide_title,
            'guide_url' => Str::slug($request->guide_title),
            'guide_content' => $request->guide_content,
            'created_at' => strtotime(Carbon::now()),
            'created_by' => Auth::guard('admin')->user()->id

        ];

        if ($request->checked_guide == 'on') {
            $data['guide_type'] = 'N';
        } else $data['guide_type'] = 'I';


        // xử lí ảnh
        if ($request->hasFile('file')) {
            $imageName = Str::random(5) . time() . '.' . $request->file->getClientOriginalExtension();
            $filePath = "/uploads/admin/guide/";
            $request->file->move(public_path($filePath), $imageName);
            $data['image_url'] = $filePath;
        }
        // Helper::create_admin_log(82, $data);
        UserGuide::create($data);
        Toastr::success('Thêm thành công');
        return redirect(route('admin.guide.list'));

    }


    public function trash_guide(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }
        $trash_Guide = UserGuide::onlyIsDeleted()
            ->leftJoin('admin', 'user_guide.created_by', '=', 'admin.id')
            ->select('user_guide.id', 'user_guide.image_url', 'user_guide.guide_title', 'admin.admin_fullname', 'user_guide.guide_content', 'user_guide.created_by', 'admin.created_at');
        $trash_Guide = $trash_Guide->orderBy('id', 'desc')->paginate($items);

        return view('Admin/Guide/TrashGuide', compact('trash_Guide'));
    }

    public function delete_guide($id)
    {
        $guide = UserGuide::findOrFail($id);
        $guide->delete();
        // Helper::create_admin_log(83, ['is_deleted' => 1]);

        Toastr::success('Chuyển vào thùng rác thành công');
        return back();
    }

    public function untrash_guide($id)
    {
        $guide = UserGuide::onlyIsDeleted()->findOrFail($id);
        $guide->restore();
        // Helper::create_admin_log(84, ['is_deleted' => 0]);

        Toastr::success('Khôi phục thành công');
        return back();

    }

    public function edit_guide($id)
    {
        $guide = UserGuide::findOrFail($id);
        // $roles = DB::table('role')->select('id', 'role_name')->where('is_deleted', 0);
        // if (Auth::guard('admin')->user()->admin_type != 1){
        // $roles = $roles->where('id','>', 1);

        // $roles = $roles->get();

        return view('Admin.Guide.EditGuide', compact('guide'));
    }

    public function post_guide(Request $request, $id)
    {
        $guide = UserGuide::findOrFail($id);

        $validate = $request->validate([
            'guide_title' => 'required',
            // 'guide_url'=> 'required',
            'file' => 'image',
            'guide_content' => 'required',
        ], [

            'guide_title.required' => '*Vui lòng nhập tiêu đề',

            'file.required' => '*Vui lòng chọn ảnh ',
            'file.image' => '* Vui lòng chọn ảnh ',
            'guide_content.required' => '*Vui lòng thêm nội dung',
        ]);

        $data = [
            'guide_title' => $request->guide_title,
            'guide_url' => Str::slug($request->guide_title),
            'guide_content' => $request->guide_content,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->user()->id

        ];

        if ($request->checked_guide == 'on') {
            $data['guide_type'] = 'N';
        } else $data['guide_type'] = 'I';


// xử lí ảnh
        if ($request->hasFile('file')) {
            // kiểm tra ảnh có tồn tại hay không , kiểm tra trong link có hay không thỏa mới tiến hành xóa
            File::delete($guide->image_url);
            //Luu image moi
            $imageName = time() . '.' . $request->file->getClientOriginalExtension();
            $fileDir = "/uploads/admin/guide/";
            $request->file->move(public_path($fileDir), $imageName);
            $data['image_url'] = $fileDir.$imageName;
        }

        // Helper::create_admin_log(85, $data);
        $guide->update($data);
        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.guide.list'));

    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        UserGuide::query()
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

        UserGuide::onlyIsDeleted()
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

        UserGuide::onlyIsDeleted()
            ->find($ids)
            ->each(function($promotion) {
                $promotion->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function View_guide($id)
    {
        $guide = UserGuide::findOrFail($id);
        return view('Admin.Guide.ViewGuide', compact('guide'));
    }
}
