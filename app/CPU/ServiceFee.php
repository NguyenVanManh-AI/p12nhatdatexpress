<?php

namespace App\CPU;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceFee
{

    /**
     * upgrade account
     * @return array
     */
    public static function upgrade_account()
    {
        $user = Auth::guard('user')->user();
        $statusData = [
            'status' => false,
            'message' => 'Nâng cấp tài khoản không thành công, vui lòng liên hệ Admin!'
        ];

        $serviceFeeID = 0;
        $user->user_type_id == 2?$serviceFeeID = 8:0;
        $user->user_type_id == 3?$serviceFeeID = 9:0;

        #check service availability with user
        $serviceInfo = DB::table('service_fee')->where('id', $serviceFeeID)->first();
        if (!$serviceInfo) {
            $statusData['status'] = false;
            $statusData['message'] = "Dịch vụ không khả dụng!";
            return $statusData;

        }

        #check user coin account balance
        $userCoinAmount = DB::table('user')->where('id', $user->id)->value('coint_amount');
        if ($serviceInfo->service_coin > $userCoinAmount)
        {
            $statusData['status'] = false;
            $statusData['message'] = "Không đủ số coin để nâng cấp tài khoản!";
            return $statusData;

        }

        DB::beginTransaction();
        try {
            #transaction data of service
            $transactionData = [
                'user_id' => $user->id,
                'transaction_type' => 'S',
                'sevice_fee_id' => $serviceInfo->id,
                'coin_amount' => $serviceInfo->service_coin??0,
                'total_coin_amount' => $serviceInfo->service_coin??0,
                'created_at' => time(),
                'created_by' => $user->id
            ];
            $transStatus = DB::table('user_transaction')->insert($transactionData);
            if (!$transStatus)
            {
                $statusData['status'] = false;
                $statusData['message'] = "Thao tác không thành công, vui lòng liên hệ Admin!";
                return $statusData;

            }

            #update user info with is_highlight = 1 and highligh_time = time of service
            $userData = [
                'is_highlight' => 1,
                'highlight_time' => time() + $serviceInfo->existence_time,
                'coint_amount' => $userCoinAmount - $serviceInfo->service_coin??0
            ];
            $userUpdateStatus = DB::table('user')->where('id', $user->id)->update($userData);
            if (!$userUpdateStatus)
            {
                DB::rollback();
                $statusData['status'] = false;
                $statusData['message'] = "Thao tác không thành công, vui lòng liên hệ Admin!";
                return $statusData;
            }

            Auth::guard('user')->user()->fresh();
            DB::commit();
            $statusData['status'] = true;
            $statusData['message'] = "Nâng cấp tài khoản thành công!";



        }
        catch (Exception $ex)
        {
            DB::rollback();
            $statusData['status'] = false;
            $statusData['message'] = "Thao tác không thành công, vui lòng liên hệ Admin!";

        }
        finally {
            return $statusData;

        }

    }

    /**
     * Mua dich vu dang tin
     * @param $service_id
     * @param $classifiedId = null
     * 
     * @return array
     */
    public static function classified_fee($service_id, $classifiedId = null)
    {
        $user = Auth::guard('user')->user();
        $statusData = [
            'status' => false,
            'message' => 'Mua dịch vụ, vui lòng liên hệ Admin!',
            'service' => null
        ];

        $serviceInfo = DB::table('service_fee')->where('id', $service_id)->first();
        if (!$serviceInfo) {
            $statusData['status'] = false;
            $statusData['message'] = 'Không tồn tại dịch vụ';
            return $statusData;
        }
        else {
            $user = DB::table('user')->where('id', $user->id)->first();
            $remainCoin = $user->coint_amount - $serviceInfo->service_coin;

            if ($remainCoin < 0) {
                $statusData['status'] = false;
                $statusData['message'] = 'Tài khoản không đủ coin!';
                return $statusData;
            }

            $serviceData = [
                'user_id' => $user->id,
                'transaction_type' => 'S',
                'sevice_fee_id' => $serviceInfo->id,
                'coin_amount' => $serviceInfo->service_coin,
                'total_coin_amount' =>  $serviceInfo->service_coin,
                'created_at' => time(),
                'created_by' => $user->id,
                'classified_id' => $classifiedId
            ];

            DB::beginTransaction();
            try {
                #tru coin trong tai khoan
                DB::table('user')->where('id', $user->id)->update(['coint_amount' => $remainCoin]);
                DB::table('user_transaction')->insert($serviceData);
                DB::commit();
                $statusData['status'] = true;
                $statusData['message'] = 'Mua dịch vụ thành công!';
                $statusData['service'] = $serviceInfo;

            }
            catch (\Exception $exception) {
                DB::rollBack();
                $statusData['status'] = false;
                $statusData['message'] = 'Mua dịch vụ không thành công!';

            }
            finally {
                return $statusData;
            }
        }


    }


}
