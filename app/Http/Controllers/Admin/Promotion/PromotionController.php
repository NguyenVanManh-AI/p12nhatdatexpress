<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\CreatePromotionRequest;
use App\Http\Requests\Admin\UpdatePromotionRequest;
use App\Models\Promotion;
use App\Models\User;
use App\Models\User\UserVoucher;
use App\Services\Admins\PromotionService;

class PromotionController extends Controller
{
    private PromotionService $promotionService;

    public function __construct()
    {
        $this->promotionService = new PromotionService;
    }

        # Thêm mã khuyến mãi
        public function store(CreatePromotionRequest $request)
        {
            if($request->radio_button == null && $request->list_users == null) {
                $request['radio_button'] = 'is_all';
            }

            $date_from = $request->date_from ?: now();
            $date_to = $request->date_to ?: now()->addYears(10);

            if($request->radio_button == "is_all" && $request->radio_button != "is_private" && $request->list_users == null){ //Mã khuyến mãi sử dụng cho tất cả mọi người
                for ($i = 1; $i <= $request->quanlity_code; $i++) {
                    Promotion::create(
                        [
                            'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                            'promotion_type'=>$request->promotion_type,
                            'value'=>$request->value,
                            'promotion_unit'=>0,
                            'num_use'=>$request->num_use,
                            'used'=>0,
                            'is_all'=>1,
                            'date_from'=>date(strtotime($date_from)),
                            'date_to'=>date(strtotime($date_to)),
                            'created_at'=>time(),
                            'created_by'=>Auth::guard('admin')->user()->id
                        ]
                    );
                }
                Toastr::success('Thêm thành công');
                return redirect()->route('admin.promotion.index');

            }else if($request->radio_button == "is_private" && $request->radio_button != "is_all" && $request->list_users == null){ //mã khuyến mãi cho trang
                for ($i = 1; $i <= $request->quanlity_code; $i++) {
                    Promotion::create(
                        [
                            'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                            'promotion_type'=>$request->promotion_type,
                            'value'=>$request->value,
                            'promotion_unit'=>0,
                            'num_use'=>$request->num_use,
                            'used'=>0,
                            'user_get'=>0,
                            'is_private'=>1,
                            'date_from'=>date(strtotime($date_from)),
                            'date_to'=>date(strtotime($date_to)),
                            'created_at'=>time(),
                            'created_by'=>Auth::guard('admin')->user()->id
                        ]
                    );
                }

                Toastr::success('Thêm thành công');
                return redirect()->route('admin.promotion.index');
            }else if($request->list_users != null){//mã chỉ định người
                for ($i = 0; $i < count($request->list_users); $i++) {
                    $infoVoucher = Promotion::create(
                        [
                            'promotion_code' => substr(strtoupper(Str::uuid()), 0, 6),
                            'promotion_type'=>$request->promotion_type,
                            'value'=>$request->value,
                            'promotion_unit'=>0,
                            'num_use'=>$request->num_use,
                            'used'=>0,
                            'user_id_use'=>$request->list_users[$i],
                            'date_from'=>date(strtotime($date_from)),
                            'date_to'=>date(strtotime($date_to)),
                            'created_at'=>time(),
                            'created_by'=>Auth::guard('admin')->user()->id
                        ]
                    );

                    UserVoucher::create(
                        [
                            'voucher_code' => $infoVoucher->promotion_code,
                            'voucher_name'=>'Voucher',
                            'amount'=>$infoVoucher->num_use,
                            'amount_used'=>0,
                            'voucher_type'=>$infoVoucher->promotion_type,
                            'voucher_percent'=>$infoVoucher->value,
                            'receipt_date'=>$infoVoucher->created_at,
                            'start_date'=>time(),
                            'end_date'=>$infoVoucher->date_to,
                            'user_id'=>$request->list_users[$i],
                            'created_by'=>Auth::guard('admin')->user()->id
                        ]
                    );
                }

                Toastr::success('Thêm thành công');
                return redirect()->route('admin.promotion.index');
            }
        }

    public function index(Request $request)
    {
        $promotions = $this->promotionService->index($request);

        $trashCountRequest = new Request([
            'request_list_scope' => $request->request_trash_list_scope
        ]);
        $trashCount = $this->promotionService->index($trashCountRequest, 'only')->total();

        return view('admin.promotions.index', [
            'promotions' => $promotions,
            'countTrash' => $trashCount
        ]);
    }

    public function trash(Request $request)
    {
        $promotions = $this->promotionService->index($request, 'only');

        return view('admin.promotions.trash', [
            'promotions' => $promotions,
        ]);
    }

    public function create()
    {
        $list_users = User::select('id', 'username')->orderBy('id','desc')->get();

        return view('admin.promotions.create', [
            'list_users' => $list_users,
            'promotion' => new Promotion([])
        ]);
    }

    public function edit(Request $request, $id)
    {
        $promotion = $this->promotionService
            ->getPermissionQuery($request)
            ->findOrFail($id);

        return view('admin.promotions.edit', [
            'promotion' => $promotion
        ]);
    }

    public function update(UpdatePromotionRequest $request,$id)
    {
        $promotion = $this->promotionService
            ->getPermissionQuery($request)
            ->findOrFail($id);

        $this->promotionService->update($promotion, $request);

        Toastr::success('Sửa thành công');
        return back();
    }

    public function deleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Promotion::query()
            ->find($ids)
            ->each(function($promotion) {
                $promotion->delete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function restoreMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Promotion::onlyIsDeleted()
            ->find($ids)
            ->each(function($promotion) {
                $promotion->restore();
            });

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Promotion::onlyIsDeleted()
            ->find($ids)
            ->each(function($promotion) {
                $promotion->forceDelete();
            });

        Toastr::success('Xóa thành công');
        return back();
    }
}

