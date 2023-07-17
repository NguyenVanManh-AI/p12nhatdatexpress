<?php

namespace App\Http\Controllers\User;

use App\CPU\Voucher;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Deposit\DepositPackageRequest;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;


class PackageController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    /*
     * Quan ly goi tin
     */
    public function index()
    {
        $user = Auth::guard('user')->user();

        #Phat sinh ma giao dich
        do {
            $params['deposit_code'] = random_string(10);
        }
        while (DB::table('user_deposit')->where('deposit_code', $params['deposit_code'])->value('deposit_code'));
        session(['deposit_code' => $params['deposit_code']]);

        #Danh sach goi tin
        $params['packages'] = DB::table('classified_package')->where('is_deleted', 0)->get();

        #Danh sach phuong thuc thanh toan
        $payment_methods = DB::table('payment_method')->get();
        foreach ($payment_methods as $payment_method) {
            $params['payment_method'][$payment_method->payment_name] = unserialize($payment_method->payment_param);
        }

        #Goi tin dang su dung
        $package = $this->userService->getCurrentBalance($user);
        $normalPendingCount = $this->userService->getClassifiedPackagePendingCount($user);
        $vipPendingCount = $this->userService->getClassifiedPackagePendingCount($user, 'vip');
        $highLightPendingCount = $this->userService->getClassifiedPackagePendingCount($user, 'highlight');
        $package['normal'] = $package->package_id != 1
                ? 'không giới hạn'
                : max((int) $package->classified_normal_amount - $normalPendingCount, 0);
        $package['vip'] = max((int) $package->vip_amount - $vipPendingCount, 0);
        $package['highlight'] = max((int) $package->highlight_amount - $highLightPendingCount, 0);
        $params['current_package'] = $package;

        #Thony ke tin dang dang hien thi
        $params['total_show_classified'] = DB::table('classified')
            ->where('user_id', $user->id)
            ->where('confirmed_status', 1)
            ->where('is_deleted', 0)
            ->where('is_show', 1)
            ->where('is_block', 0)
            ->where('expired_date', '>=', time())
            ->count();

        #Thong ke tin dang dang cho duyet
        $params['total_confirm_classified'] = DB::table('classified')
            ->where('user_id', $user->id)
            ->where('confirmed_status', 0)
            ->where('is_deleted', 0)
            ->where('is_block', 0)
            ->where('expired_date', '<', time())
            ->count();

        #Thong ke tin dang khonyg duoc duyet
        $params['total_not_confirm_classified'] = DB::table('classified')
            ->where('user_id', $user->id)
            ->where('confirmed_status', 2)
            ->where('is_deleted', 0)
            ->where('is_block', 0)
            ->where('expired_date', '<', time())
            ->count();

        #Thong ke tin dang da het han
        $params['total_expired_date'] = DB::table('classified')
            ->where('user_id', $user->id)
            ->where('confirmed_status', 1)
            ->where('is_deleted', 0)
            ->where('is_show', 1)
            ->where('is_block', 0)
            ->where('expired_date', '<', time())
            ->count();

        return View::make('user.classified.package', $params);

    }

    /**
     * Thanh toan mua goi tin
     * @param DepositPackageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deposit_package(DepositPackageRequest $request)
    {
        #Kiem tra ma giao dich
        if (session('deposit_code') != $request->deposit_code) {
            Toastr::error('Giao dịch không thành công');
            return redirect()->route('user.package');
        }

        #Kiem tra goi tin mua hop le
        $package = DB::table('classified_package')->where('id', $request->package_id)->first();
        if (!$package) {
            Toastr::error('Gói tin không tồn tại!');
            return redirect()->back();
        }

        $user = Auth::guard('user')->user();
        #Thanh toán bằng express coin
        if ($request->payment_method == 0) {
            #kiem tra khuyen mai
            $voucherInfo = Voucher::checkVoucherValid(0, $request->deposit_voucher);
            #So phan tram khuyen mai
            $discountPercent = $voucherInfo['voucherPercent'];
            #So coin duoc khuyen mai
            $discountCoin = 0.01 * $discountPercent * $package->discount_coin_price;
            #Tong so coin thaannh toan
            $totalCoinAmount = $package->discount_coin_price - $discountCoin;
            #So du coin hien tai
            $userCoinAmount = DB::table('user')->where('id', $user->id)->value('coint_amount');
            #Kiem tra tai khoan du coins thanh toan
            if ($totalCoinAmount > $userCoinAmount) {
                Toastr::error('Tài khoản không đủ số coin để thực hiện giao dịch');
                return redirect()->back();

            }

            DB::beginTransaction();
            try {
                #Cap nhat so coin sau khi mua goi
                DB::table('user')->where('id', $user->id)->update(['coint_amount' => $userCoinAmount - $totalCoinAmount]);

                $transaction_data = [
                    'user_id' => $user->id,
                    'transaction_type' => 'I',
                    'post_package_id' => $package->id,
                    'coin_amount' => $package->discount_coin_price,
                    'user_voucher_id' => $voucherInfo['id'],
                    'voucher_discount_percent' => $voucherInfo['voucherPercent'],
                    'voucher_discount_coin' => $discountCoin,
                    'total_coin_amount' => $totalCoinAmount,
                    'created_at' => time(),
                    'created_by' => $user->id
                ];

                $transaction = DB::table('user_transaction')->insertGetId($transaction_data);
                DB::table('user_balance')->where('user_id', $user->id)
                    ->where('package_id', '<>', 1)
                    ->update(['status' => 0]);
                $balance_data = [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'transaction_id' => $transaction,
                    'vip_amount' => $package->vip_amount ?? 0,
                    'highlight_amount' => $package->highlight_amount ?? 0,
                    'package_from_date' => time(),
                    'package_to_date' => time() + $package->duration_time,
                    'created_at' => time(),
                    'created_by' => $user->id,
                    'status' => 1,
                ];
                DB::table('user_balance')->insert($balance_data);

                DB::commit();
                Toastr::success('Giao dịch thành công, vui lòng chờ Admin xác nhận');


            } catch (\Exception $e) {
                DB::rollBack();
                Toastr::error('Mua gói tin không thành công, vui lòng liên hệ Admin!');

            } finally {
                return redirect()->route('user.package');
            }
        }

        #Thanh toán bằng tiền
        DB::beginTransaction();
        try {
            $transaction_data = [
                'user_id' => $user->id,
                'transaction_type' => 'I',
                'total_price' => $package->discount_price,
                'post_package_id' => $package->id,
                'created_at' => time(),
                'created_by' => $user->id
            ];
            $transaction = DB::table('user_transaction')->insertGetId($transaction_data);

            $deposit_data = [
                'user_id' => $user->id,
                'is_transferred' => 1,
                'deposit_time' => time(),
                'deposit_type' => 'I',
                'payment_method_id' => $request->payment_method,
                'deposit_code' => $request->deposit_code,
                'deposit_amount' => $package->discount_price,
                'user_transaction_id' => $transaction
            ];
            DB::table('user_deposit')->insert($deposit_data);

            $balance_data = [
                'user_id' => $user->id,
                'package_id' => $package->id ?? null,
                'transaction_id' => $transaction,
                'vip_amount' => $package->vip_amount ?? 0,
                'highlight_amount' => $package->highlight_amount ?? 0,
                'package_from_date' => time(),
                'package_to_date' => time() + $package->duration_time,
                'created_at' => time(),
                'created_by' => $user->id,
                'status' => 0
            ];
            DB::table('user_balance')->insert($balance_data);

            DB::commit();
            Toastr::success('Thanh toán thành công, vui lòng chờ Admin xác nhận');

        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error('Mua gói tin không thành công, vui lòng liên hệ Admin!');

        } finally {
            return redirect()->route('user.package');

        }
    }
}
