<?php

namespace App\CPU;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * check voucher infor
 */
class Voucher
{

    /**
     * Check voucher valid
     * @param int $voucherType
     * @param string $voucherCode
     * @return array
     */
    public static function checkVoucherValid($voucherType, $voucherCode)
    {
        $voucherResult = [
            'status' => false,
            'message' => 'Voucher không khả dụng!',
            'id' => null,
            'voucherPercent' => 0
        ];

        $user = Auth::guard('user')->user();
        $voucher = DB::table('user_voucher')
            ->where('voucher_code', $voucherCode)
            ->where('user_id', $user->id)
            ->where('start_date', '<=', time())
            ->where('end_date', '>=', time())
            ->where('voucher_type', $voucherType??1)
            ->whereRaw('amount > amount_used')
            ->first();

        if ($voucher) {
            $voucherResult['status'] = true;
            $voucherResult['message'] = "Voucher khả dụng";
            $voucherResult['id'] = $voucher->id;
            $voucherResult['voucherPercent'] = $voucher->voucher_percent;

        }

        return $voucherResult;

    }
}
