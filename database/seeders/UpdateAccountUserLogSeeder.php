<?php

namespace Database\Seeders;

use App\Enums\UserLogType;
use App\Models\UserLog;
use Illuminate\Database\Seeder;

class UpdateAccountUserLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userLogs = [
            // delete the account
            [
                'key' => 'user-delete-account',
                'log_content' => 'Xoá tài khoản',
                'log_icon' => 'fas fa-user-times bg-danger'
            ],
            // restore the account
            [
                'key' => 'user-restore-account',
                'log_content' => 'Khôi phục tài khoản',
                'log_icon' => 'fas fa-user-check bg-success'
            ],
            // update avatar
            [
                'key' => 'user-update-avatar',
                'log_content' => 'Cập nhật ảnh đại diện',
                'log_icon' => 'fas fa-user bg-success'
            ],
            // update user deploying projects | cập nhật dự án đang triển khai
            [
                'key' => 'user-deploying-projects',
                'log_content' => 'Cập nhật dự án đang triển khai',
                'log_icon' => 'fas fa-project-diagram bg-success'
            ],
            [
                'key' => 'user-add-project-request',
                'log_content' => 'Yêu cầu dự án',
                'log_icon' => 'fas fa-project-diagram bg-warning'
            ],
        ];

        foreach ($userLogs as $log) {
            $content = data_get($log, 'log_content');
            $userLog = UserLog::firstWhere('log_content', $content);

            // update old log not have key
            if ($userLog) {
                $userLog->update([
                    'key' => data_get($log, 'key'),
                    'log_type' => data_get($log, 'log_type', UserLogType::UPDATE_INFO),
                    'log_icon' => data_get($log, 'log_icon'),
                ]);

                // remove duplicates content
                UserLog::where('log_content', $content)
                    ->where('id', '!=', $userLog->id)
                    ->forceDelete();
            } else {
                // update or create new by key & type
                UserLog::updateOrCreate([
                    'key' => data_get($log, 'key'),
                    'log_type' => data_get($log, 'log_type', UserLogType::UPDATE_INFO),
                ], [
                    'log_content' => data_get($log, 'log_content'),
                    'log_icon' => data_get($log, 'log_icon'),
                ]);
            }
        }
    }
}
