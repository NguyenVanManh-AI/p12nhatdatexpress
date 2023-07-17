<?php

namespace App\Http\Controllers\Admin\AddCoin;

use App\Helpers\CollectionHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Express\AddExpressCoinRequest;
use App\Models\User;
use App\Models\User\UserDeposit;
use App\Models\User\UserLevel;
use App\Models\User\UserTransaction;
use App\Models\UserCoinRefReceipt;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AddCoinController extends Controller
{
    private $ktocoin = 10; // 1000 = 10 coin => 100.000 = 1000 coin

    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        };

        $list_buy = UserTransaction::query()
            ->leftJoin('user', 'user_transaction.user_id', '=', 'user.id')
            ->leftJoin('user_detail', 'user.id', '=', 'user_detail.user_id')
            ->leftJoin('user_deposit', 'user_transaction.id', '=', 'user_deposit.user_transaction_id')
            ->select('user_transaction.id', 'user_deposit.confirm_by', 'user_deposit.deposit_status',
                'user_detail.fullname', 'user.email', 'user.phone_number', 'user_deposit.deposit_code', 'user_deposit.deposit_amount',
                'user_transaction.voucher_discount_percent', 'user_transaction.created_at', 'user_deposit.id as deposit_id')
            ->where('user_deposit.is_deleted', '=', 0)
            ->where('user_deposit.deposit_type', '=', 'C');
        if ($request->request_list_scope == 2) {
            $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
            $list_buy = $list_buy->join('admin', 'user_transaction.confirm_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) {
            $admin_id = Auth::guard('admin')->user()->id;
            $list_buy = $list_buy->join('admin', 'user_transaction.confirm_by', '=', 'admin.id')->where('bill_service.confirm_by', '=', $admin_id);
        }

        $list_buy = $list_buy->orderByDesc('user_transaction.id')->get();

        // lọc theo từ khóa
        if (isset($request->keyword) && $request->keyword != "") {
            $key = $request->keyword;
            $list_buy = collect($list_buy)->filter(function ($item) use ($key) {
                return false !== stristr($item->fullname, $key) || stristr($item->phone_number, $key) || stristr($item->deposit_code, $key);
            });
        }
        if (($request->has('start_day') && $request->start_day != "") || ($request->has('end_day') && $request->end_day != "")) {
            if ($request->start_day == "") {
                $start = strtotime(Carbon::parse($request->end_day));
                $end = strtotime(Carbon::parse($request->end_day)->addDay(1));
            } else if ($request->end_day == "") {
                $start = strtotime(Carbon::parse($request->start_day));
                $end = strtotime(Carbon::parse($request->start_day)->addDay(1));
            } else {
                $start = strtotime(Carbon::parse($request->start_day));
                $end = strtotime(Carbon::parse($request->end_day)->addDay(1));
            }
            $list_buy = $list_buy->where('created_at', '>=', $start);
            $list_buy = $list_buy->where('created_at', '<=', $end);
        }
        if (isset($request->money) && $request->money != "") {

            $list_buy = $list_buy->where('deposit_amount', '=', $request->money);
        }

        $list_buy = CollectionHelper::paginate($list_buy, $items);
        $trashCount = UserDeposit::onlyIsDeleted()->count();

        return view('Admin.AddCoin.ListAddCoin', compact('list_buy', 'trashCount'));
    }

    // Change status
    public function change_status($status, $id)
    {
        $deposit = UserDeposit::findOrFail($id);

        if ($deposit->deposit_status != 0) {
            Toastr::error("Giao dịch đã được xác nhận");
            return back();
        }

        try {
            DB::beginTransaction();
            // Update deposit

            $deposit->update([
                'deposit_status' => $status,
                'confirm_by' => Auth::guard('admin')->id(),
                'is_confirm' => 1,
                'confirm_time' => time(),
                'one_time_confirm_token' => null
            ]);

            // Update user_coin_ref_receipt
            if ($deposit->userCoinRefReceipt) {
                $deposit->userCoinRefReceipt()->update([
                    'status' => $status,
                ]);
            }

            // Update coin user
            $this->progress_coin($deposit, $status);

            // Helper::create_admin_log(63, [
            //     'id' => $id,
            //     'deposit_status' => $status,
            //     'confirm_by' => Auth::guard('admin')->id(),
            //     'is_confirm' => 1,
            //     'confirm_time' => time()
            // ]);

            DB::commit();

            Toastr::success("Cập nhật trạng thái thành công");
        } catch (\Exception $exception) {
            Toastr::error("Xảy ra lỗi bất định");
            DB::rollBack();
        }

        return back();
    }

    public function trash(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        };
        $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
        $list_buy = UserTransaction::query()
            ->join('user', 'user_transaction.user_id', '=', 'user.id')
            ->join('user_detail', 'user.id', '=', 'user_detail.user_id')
            ->join('user_deposit', 'user_transaction.id', '=', 'user_deposit.user_transaction_id')
            ->select('user_transaction.id', 'user_deposit.confirm_by', 'user_deposit.deposit_status', 'user_detail.fullname',
                'user.email', 'user.phone_number', 'user_deposit.deposit_code', 'user_deposit.deposit_amount', 'user_transaction.voucher_discount_percent', 'user_transaction.created_at', 'user_deposit.id as deposit_id')
            ->where('user_deposit.is_deleted', '=', 1)
            ->where('user_deposit.deposit_type', '=', 'C');

        if ($request->request_list_scope == 2) {
            $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
            $list_buy = $list_buy->join('admin', 'user_transaction.confirm_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) {
            $admin_id = Auth::guard('admin')->user()->id;
            $list_buy = $list_buy->join('admin', 'user_transaction.confirm_by', '=', 'admin.id')->where('bill_service.confirm_by', '=', $admin_id);
        }
        $list_buy = $list_buy->orderByDesc('user_transaction.id')->get();
        $list_buy = CollectionHelper::paginate($list_buy, $items);

        // $count_trash = DB::table('user_deposit')->where('is_deleted', 1)->count();
        $count_trash = UserDeposit::onlyIsDeleted()->count();

        return view('Admin.AddCoin.TrashAddCoin', compact('list_buy', 'count_trash'));
    }

    public function delete_coin($id)
    {
        $deposit = UserDeposit::findOrFail($id);
        $deposit->delete();

        // Helper::create_admin_log(64, ['id' => $id, 'is_deleted' => 1]);
        Toastr::success('Chuyển vào thùng rác thành công');
        return back();
    }

    public function untrash_coin($id)
    {
        $deposit = UserDeposit::onlyIsDeleted()->findOrFail($id);
        $deposit->restore();

        // Helper::create_admin_log(65, ['id' => $id, 'is_deleted' => 0]);
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
            $deposit = UserDeposit::find($item);
            if (!$deposit) continue;
            $deposit->delete();
            // Helper::create_admin_log(64, ['id' => $item, 'is_deleted' => 1]);
        }

        Toastr::success(' Xóa thành công');
        return back();

    }

    public function untrash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $deposit = UserDeposit::onlyIsDeleted()
                ->find($item);
            if (!$deposit) continue;

            $deposit->restore();
            // Helper::create_admin_log(65, ['id' => $item, 'is_deleted' => 0]);
        }

        Toastr::success('Khôi phục thành công');
        return back();

    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        UserDeposit::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

    public function add_coin()
    {
        return view('Admin.AddCoin.AddCoin');
    }

    public function post(AddExpressCoinRequest $request)
    {
        $user = User::where('username', $request->username_express)->firstOrFail();
        $percentSpecial = data_get($user->level, 'percent_special');
        $totalCointAmount = $request->amount_express / 100 + (($request->amount_express / 100) * ($percentSpecial / 100));

        $arr_transaction = [
            'user_id' => $user->id,
            'transaction_type' => 'C',
            'coin_amount' => $request->amount_express / 100,
            'user_level_percent' => $percentSpecial,
            'total_coin_amount' => $totalCointAmount,
            'total_price' => $request->amout_express,
            'created_at' => time(),
        ];

        UserTransaction::create($arr_transaction);
        $user->update([
            'coint_amount' => $user->coint_amount + $totalCointAmount
        ]);

        Toastr::success('Nạp thành công');
        return redirect(route('admin.coin.list'));
    }

    public function progress_coin($deposit, $status)
    {
        if ($deposit) {
            $coin = $this->ktocoin * ($deposit->deposit_amount / 1000);
            switch ($status) {
                // 1: Duyệt
                // 2: Không duyệt
                case 1:
                    // Add coin for user
                    $user = $deposit->user;

                    $user->update([
                        'coint_amount' => DB::raw("coint_amount + $coin"),
                    ]);

                    // Add coin for ref user
                    $userCoinRefReceipt = $deposit->userCoinRefReceipt;
                    if ($userCoinRefReceipt && $userCoinRefReceipt->status == 1 && $userCoinRefReceipt->user) {
                        $userCoinRefReceipt->user()->update([
                            'coint_amount' => DB::raw("coint_amount + $userCoinRefReceipt->receipt_coin"),
                        ]);
                    }
                    // $ref_coin = UserCoinRefReceipt::firstWhere('user_deposit_id', $deposit->id);
                    // if ($ref_coin && $ref_coin->status == 1) {
                    //     User::where('id', $ref_coin->user_ref_id)->update([
                    //         'coint_amount' => DB::raw("coint_amount + $ref_coin->receipt_coin"),
                    //     ]);
                    // }
                    break;

//              Only status 0 can be change to 1 or 2 (2: dont need more action)
//                case 2:
//                    DB::table('user')->where('id', $deposit->user_id)->update([
//                        'coint_amount' => DB::raw("coint_amount - $coin"),
//                    ]);
//                    break;
            }
        }
    }

}
