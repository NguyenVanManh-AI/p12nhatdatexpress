<?php

namespace App\Http\Controllers\Admin\PersonalPage;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\UserDetail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private const LOG = [
      'SHOW' => 203,
      'HIDE' => 204,
      'APPROVE' => 191,
      'DECLINE' => 192,
      'REMOVE' => 193,
    ];

    private const STATUS = [
        'HIDDEN' => 0,
        'SHOW' => 1,
        'APPROVED' => 2,
    ];

    # Approval list banner
    public function approve_banner_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $data = [ 'banner_status' => self::STATUS['APPROVED']];
            $detail = UserDetail::find($item);
            if (!$detail) continue;

            $detail->update($data);
            // Helper::create_admin_log(self::LOG['APPROVE'], $data);
        }
        Toastr::success('Duyệt thành công');
        return back();
    }

    # Decline list banner
    public function un_approve_banner_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $data = ['banner_status' => self::STATUS['HIDDEN']];
            $detail = UserDetail::find($item);
            if (!$detail) continue;

            $detail->update($data);
            // Helper::create_admin_log(193,$data);
        }
        Toastr::success('Ẩn banner thành công');
        return back();
    }

    # Remove list banner
    public function close_banner_list(Request $request){
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $data = [
                'banner_status' => 0,
                'banner_left' => null,
                'banner_right' => null
            ];
            $detail = UserDetail::find($item);
            if (!$detail) continue;

            $detail->update($data);
            // Helper::create_admin_log(self::LOG['REMOVE'], $data);
        }
        Toastr::success('Xoá banner thành công');
        return back();
    }

    # Duyệt banner
    public function approve_banner($id){
        $detail = UserDetail::findOrFail($id);
        $data = [ 'banner_status' => self::STATUS['APPROVED']];

        $detail->update($data);
        // Helper::create_admin_log(self::LOG['APPROVE'], $data);

        Toastr::success('Duyệt thành công');
        return back();
    }

    # Xóa banner
    public function close_banner($id): RedirectResponse
    {
        $detail = UserDetail::findOrFail($id);
        $data = [
            'banner_status' => 0,
            'banner_left' => null,
            'banner_right' => null
        ];
        $detail->update($data);
        // Helper::create_admin_log(self::LOG['REMOVE'],$data);

        Toastr::success('Xóa banner thành công');
        return back();
    }

    # Không duyệt banner
    public function un_approve_banner($id): RedirectResponse
    {
        $detail = UserDetail::findOrFail($id);
        $data = [ 'banner_status' => self::STATUS['HIDDEN'] ];
        $detail->update($data);
        // Helper::create_admin_log(193,$data);

        Toastr::success('Ẩn banner thành công');
        return back();
    }

    # Hiển thị banner
    public function show_banner($id): RedirectResponse
    {
        $detail = UserDetail::findOrFail($id);
        $data = [ 'banner_status' => 1];
        $detail->update($data);
        // Helper::create_admin_log(self::LOG['SHOW'], $data);

        Toastr::success('Hiển thị banner thành công');
        return back();
    }

    # Hiển thị danh sách banner
    public function show_banner_list(Request $request): RedirectResponse
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            $detail = UserDetail::find($item);
            if (!$detail) continue;

            $data = [ 'banner_status' => 1];
            $detail->update($data);
            // Helper::create_admin_log(self::LOG['SHOW'], $data);
        }
        Toastr::success('Hiển thị banner thành công');
        return back();
    }

    # Danh sách banner trang cá nhân
    public function list_banner(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)){
            $items = $request->items;
        }
        $listQuery = User::query()
        ->join('user_detail','user_detail.user_id','user.id')
        ->select('user.id','user.username', 'user.phone_number', 'user.email', 'user_detail.banner_left','user_detail.banner_right','user.created_at', 'user_detail.fullname')
        ->where(function ($query){
            $query->orWhereNotNull('user_detail.banner_right')
                ->orWhereNotNull('user_detail.banner_left');
        })
        ->orderBy('user.id','desc');

        if($request->keyword!=''){
            $listQuery->where('user_detail.fullname','LIKE',"%$request->keyword%")
                ->orWhere('user.phone_number','LIKE',"%$request->keyword%")
                ->orWhere('user.email','LIKE',"%$request->keyword%");
        }
        if($request->date_from){
            $listQuery->where('user.created_at','>' , strtotime($request->date_from));
        }
        if($request->date_to){
            $listQuery->where('user.created_at','<', strtotime($request->date_to) + 86399);
        }

        $list = $listQuery->paginate($items);
        return view('Admin/PersonalPage/ListBanner',
            [
                'list' => $list,
            ]);
    }
}
