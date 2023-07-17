<?php

namespace App\Services;

use App\Enums\AdvisoryStatus;
use App\Models\ModuleAdvisory;

class AdvisoryService
{
    /**
     * create new advisory
     * @param $model
     * @param array $data
     *
     * @return ModuleAdvisory $advisory
     */
    public function create($model, array $data)
    {
        $advisory = $model->advisories()->create([
            'ip' => data_get($data, 'ip'),
            'user_id' => data_get($data, 'user_id'),
            'fullname' => data_get($data, 'fullname'),
            'phone_number' => data_get($data, 'phone_number'),
            'email' => data_get($data, 'email'),
            'note' => data_get($data, 'note'),
            'options' => data_get($data, 'options', []),
            'status' => AdvisoryStatus::PENDING,

            'created_at' => now()
        ]);

        return $advisory;
    }

    /**
     * check added advisory
     * @param $model
     * @param array $data
     *
     * @return array
     */
    public function checkAdded($model, array $data)
    {
        $alreadySend = $model->advisories()
            ->where('email', data_get($data, 'email'))
            ->firstWhere('phone_number', data_get($data, 'phone_number'));

        if ($alreadySend) {
            return [
                'success' => false,
                'message' => 'Email và số điện thoại này đã gửi thông tin rồi',
            ];
        }

        $limitAdvisoriesPerDay = config('constants.user.limit_send_advisories_per_day', 15);

        $ip = data_get($data, 'ip');
        $userId = data_get($data, 'user_id');

        $dayAdvisories = ModuleAdvisory::where('ip', $ip)
                ->when($userId, function ($query) use ($userId) {
                    return $query->orWhere('user_id', $userId);
                })
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->count();

        $success = $dayAdvisories < $limitAdvisoriesPerDay;

        return [
            'success' => $success,
            'message' => $success
                ? 'Thành công'
                : "Chỉ được gửi tối đa $limitAdvisoriesPerDay tư vấn / ngày.",
        ];
    }
}
