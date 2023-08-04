<?php

namespace App\CPU;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Deposit
{
    static $paymentAmountList = [
        1 => 100000,
        2 => 200000,
        3 => 500000,
        4 => 1000000,
        5 => 3000000,
        6 => 5000000,
        7 => 10000000,
        8 => 20000000

    ];

    /**
     * user deposit
     * @param int $peymentMethod
     * @param string $depositCode
     * @param int $depositAmount
     * @param string $voucher
     * @param int $confirmPayment
     * @return bool
     */
    public static function deposit($paymentMethod, $depositCode, $depositAmount, $voucherCode = null,$one_time_confirm_token)
    {
        $user = Auth::guard('user')->user();
        $depositResult = ['status' => false, 'message' => null];

        #check deposit code is valid
        if (session('deposit_code') != $depositCode) {
            $depositResult['status'] = false;
            $depositResult['message'] = 'Mã giao dịch không khả dụng!';
            return $depositResult;
        }

        #deposit coin amount
        $depositCoinAmount = Deposit::$paymentAmountList[$depositAmount]*0.01;

        #get voucher infor
        $voucher = Voucher::checkVoucherValid(1, $voucherCode);
        if ($voucherCode) {
            if (!$voucher['status']) {
                $depositResult['status'] = false;
                $depositResult['message'] = 'Mã khuyến mãi không khả dụng!';
                return $depositResult;
            }
        }

        #voucher coin amount
        $voucherCoinAmount = round(0.01*$depositCoinAmount*$voucher['voucherPercent'], 0, PHP_ROUND_HALF_DOWN);

        #level coin amount
        $userLevelPercent = DB::table('user_level')->where('id', $user->user_level_id)->value('percent_special')??0;
        $userLevelCoinAmount = $userLevelPercent > 0? round(0.01*$depositCoinAmount*$userLevelPercent, 0, PHP_ROUND_HALF_DOWN):0;

        #Total coin amount
        $totalAmount =   $depositCoinAmount + $voucherCoinAmount + $userLevelPercent;

        DB::beginTransaction();
        try {
            #transaction data
            $transactionData = [
                'user_id' =>$user->id,
                'transaction_type' => 'C',
                'coin_amount' =>$depositCoinAmount,
                'user_voucher_id' => $voucher['id'],
                'voucher_discount_percent' => $voucher['voucherPercent'],
                'voucher_discount_coin' => $voucherCoinAmount,
                'user_level_percent' => $userLevelPercent,
                'user_level_coin' =>$userLevelCoinAmount,
                'total_coin_amount' => $totalAmount,
                'total_price' => Deposit::$paymentAmountList[$depositAmount],
                'created_at' => time(),
                'created_by' => $user->id,
            ];
            $transactionId = DB::table('user_transaction')->insertGetId($transactionData);

            #deposit data
            $deposit_data = [
                'user_id' => $user->id,
                'deposit_code' => $depositCode,
                'user_transaction_id' => $transactionId,
                'deposit_amount' => Deposit::$paymentAmountList[$depositAmount],
                'is_transferred' => 1,
                'deposit_time' => time(),
                'deposit_type' => 'C',
                'payment_method_id' => $paymentMethod,
                'one_time_confirm_token' => $one_time_confirm_token
            ];
            $depositId = DB::table('user_deposit')->insertGetId($deposit_data);

            #Adding ref coin to ref user
            if ($user->user_ref_id)
            {
                $user_ref_data = [
                    'user_id' => $user->user_ref_id,
                    'user_ref_id' => $user->id,
                    'deposit_ref_coin' => $depositCoinAmount,
                    'receipt_coin' =>  round($depositCoinAmount*0.15, 0, PHP_ROUND_HALF_DOWN),
                    'user_deposit_id' => $depositId,
                    'status' => 0,
                    'receipt_time' => time()
                ];
                DB::table('user_coin_ref_receipt')->insert($user_ref_data);
            }
            DB::commit();
            $depositResult['status'] = true;
            $depositResult['message'] = "Nạp coin thành công!";

        } catch (Exception $exception)
        {
            DB::rollBack();
            $depositResult['status'] = false;
            $depositResult['message'] = "Nạp coin không thành công!";
        }

        return $depositResult;
    }
}
