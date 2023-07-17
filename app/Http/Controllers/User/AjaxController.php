<?php

namespace App\Http\Controllers\User;

use App\CPU\Voucher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    /**
     * check voucher is valid when using voucher in deposit
     * @param $voucherCode string
     * @return void
     */
    public function getVoucherInfo(Request $request)
    {
        $voucherStatus = Voucher::checkVoucherValid($request->voucherType, $request->voucherCode);
        return response()->json(['voucherStatus' => $voucherStatus, 'status' => 'success'], 200);

    }

}
