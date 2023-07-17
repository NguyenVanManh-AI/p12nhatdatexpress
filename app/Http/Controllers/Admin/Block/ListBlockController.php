<?php

namespace App\Http\Controllers\Admin\Block;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Block\AddBlockRequest;
use App\Http\Requests\Admin\Block\UpdateBlockRequest;
use App\Models\ForbiddenWord;

class ListBlockController extends Controller
{
public function list_block(Request $request){
    $items = 10;
    if ($request->has('items')  && is_numeric($request->items)) {
        $items = $request->items;
    }
     //check group
     if($request->request_list_scope == 2){
        $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
        $list_block = ForbiddenWord::query()
        ->join('admin', 'forbidden_word.created_by','=','admin.id')
        ->where('admin.rol_id', $admin_role_id)
        ->select('forbidden_word.id','forbidden_word.forbidden_word','admin.admin_fullname','forbidden_word.created_at','forbidden_word.is_show','forbidden_word.is_deleted','forbidden_word.created_by');
     }
 //check self
    else if($request->request_list_scope == 3){
        $admin_id = Auth::guard('admin')->user()->id;
        $list_block = ForbiddenWord::query()

        ->join('admin', 'forbidden_word.created_by', '=', 'admin.id')
        ->select('forbidden_word.id','forbidden_word.forbidden_word','admin.admin_fullname','forbidden_word.created_at','forbidden_word.is_show','forbidden_word.is_deleted','forbidden_word.created_by')
        ->where(['forbidden_word.created_by'=>$admin_id]);
    }
 //check all
 else {
    $list_block = ForbiddenWord::query()

    ->join('admin', 'forbidden_word.created_by', '=', 'admin.id')
    ->select('forbidden_word.id','forbidden_word.forbidden_word','admin.admin_fullname','forbidden_word.created_at','forbidden_word.is_show','forbidden_word.is_deleted','forbidden_word.created_by');
}
    if ($request->keyword){
        $list_block = $list_block->where('forbidden_word', 'like', "%$request->keyword%");
    }
    $list_block = $list_block->orderBy('id','desc')->paginate($items);
    $count_trash = ForbiddenWord::onlyIsDeleted()->count();

    return view('Admin.ListBlock.ListBlock',compact('list_block','count_trash'));

}
public function trash_block(Request $request){
    $items = 10;
    if ($request->has('items')  && is_numeric($request->items)) {
        $items = $request->items;
    }
   //check group
   if($request->request_list_scope == 2){
    $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
    $trash_block = ForbiddenWord::query()
    ->join('admin', 'forbidden_word.created_by','=','admin.id')
    ->where('admin.rol_id', $admin_role_id)
    ->select('forbidden_word.id','forbidden_word.forbidden_word','admin.admin_fullname','forbidden_word.created_at','forbidden_word.is_show','forbidden_word.is_deleted','forbidden_word.created_by')
    ->onlyIsDeleted();
    }
    //check self
    else if($request->request_list_scope == 3){
        $admin_id = Auth::guard('admin')->user()->id;
        $trash_block = ForbiddenWord::query()

        ->join('admin', 'forbidden_word.created_by', '=', 'admin.id')
        ->select('forbidden_word.id','forbidden_word.forbidden_word','admin.admin_fullname','forbidden_word.created_at','forbidden_word.is_show','forbidden_word.is_deleted','forbidden_word.created_by')
        ->where(['forbidden_word.created_by'=>$admin_id])
        ->onlyIsDeleted();
    }
    //check all
    else {
    $trash_block = ForbiddenWord::query()

    ->join('admin', 'forbidden_word.created_by', '=', 'admin.id')
    ->select('forbidden_word.id','forbidden_word.forbidden_word','admin.admin_fullname','forbidden_word.created_at','forbidden_word.is_show','forbidden_word.is_deleted','forbidden_word.created_by')
    ->onlyIsDeleted();

    }
$trash_block = $trash_block->orderBy('id','desc')->paginate($items);

return view('Admin.ListBlock.TrashBlock',compact('trash_block',));
}
public function change_status($id)
{
    $forbidden = ForbiddenWord::findOrFail($id);

    $forbidden->update([
        'is_show' => $forbidden->is_show
    ]);

    // Helper::create_admin_log(76,['is_show' => $forbidden->is_show]);
    Toastr::success('Cập nhật trạng thái thành công');
    return back();
}

public function delete($id)
{
    $forbidden = ForbiddenWord::onlyIsDeleted()->findOrFail($id);
    $forbidden->forceDelete();
    // Helper::create_admin_log(77,ForbiddenWord::where('id', $id)->first());

    Toastr::success('Xóa thành công');
    return back();
}

public function delete_block($id)
{
    $forbidden = ForbiddenWord::findOrFail($id);
    //kiểm tra tk có tồn tại

    $forbidden->delete();
    // Helper::create_admin_log(78,['id'=>$id,'is_deleted' => 1]);

    Toastr::success('Chuyển vào thùng rác thành công');
    return back();
}
public function untrash_block($id){
    $forbidden = ForbiddenWord::onlyIsDeleted()
        ->findOrFail($id);

    $forbidden->restore();
    // Helper::create_admin_log(79,[
    //     'id'=>$id,
    //     'is_deleted' => 0
    // ]);

    Toastr::success('Khôi phục thành công');
    return back();

}

public function trash_list(Request $request)
    {

        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        foreach ($request->select_item as $item) {
            // ForbiddenWord::where('id', $item)->update(['is_deleted' => 1]);
            // Helper::create_admin_log(78,['id'=>$item,'is_deleted' => 1]);
            $forbidden = ForbiddenWord::findOrFail($item);
            if (!$forbidden) continue;

            $forbidden->delete();
        }
        Toastr::success(' Xóa thành công');
        return back();

    }

    public function untrash_list(Request $request)
    {


        // dd($request->all());
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        if($request->action == "restore"){

        foreach ($request->select_item as $item) {
            $forbidden = ForbiddenWord::onlyIsDeleted()
                ->find($item);

            if (!$forbidden) continue;

            $forbidden->restore();

            // ForbiddenWord::where('id', $item)->update(['is_deleted' => 0]);
            // Helper::create_admin_log(79,['id'=>$item,'is_deleted' => 0]);
        }
        Toastr::success('Khôi phục thành công');
        return back();
        }
        if($request->action == "delete"){

            foreach ($request->select_item as $item) {
                $forbidden = ForbiddenWord::find($item);

                if (!$forbidden) continue;

                $forbidden->delete();
                // Helper::create_admin_log(77,ForbiddenWord::where('id', $item)->first());
                // ForbiddenWord::where('id', $item)->delete();
            }
            Toastr::success('Xóa thành công');
            return back();
        }

    }
    public function post_block(AddBlockRequest $request){

        $data = [
            'forbidden_word' => $request->forbidden_word,
            'created_at'=>strtotime(Carbon::now()),
            'created_by'=> Auth::guard('admin')->user()->id
        ];

        // Helper::create_admin_log(80,$data);
        ForbiddenWord::create($data);

        Toastr::success('Thêm thành công');
        return back();
    }

    /**
     * Edit Forbidden word
     * @param UpdateBlockRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit_post_block(UpdateBlockRequest $request,$id){
        $forbidden = ForbiddenWord::findOrFail($id);

          $data = [
            'forbidden_word' => $request->forbidden_word,
            'updated_at'=>strtotime('now'),
            'updated_by'=> Auth::guard('admin')->id()
          ];
          $forbidden->update($data);
        //   $data['id'] =$id;
        //   Helper::create_admin_log(81,$data);
          Toastr::success('Sửa thành công');
          return back();
    }
}
