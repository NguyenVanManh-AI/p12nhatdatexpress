<?php

namespace App\Services;

use App\Models\User\UserPost;
use App\Models\User\UserPostComment;

class PersonalPageService
{
    /**
     * create report
     * @param $model
     * @param array $data
     *
     * @return $report
     */
    public function createReport($model, array $data)
    {
        return $model->reports()
            ->create([
                'user_id' => data_get($data, 'user_id'),
                'report_type' => data_get($data, 'report_type'),
                'report_content' => data_get($data, 'report_content'),
                'report_position' => data_get($data, 'report_position'),
                'report_result' => 1,
                'confirm_status' => 0,
                'report_time' => time(),
            ]);
    }
}

