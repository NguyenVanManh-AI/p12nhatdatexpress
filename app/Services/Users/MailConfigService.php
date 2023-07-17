<?php 

namespace App\Services\Users;

use App\Models\User;
use App\Models\UserMailConfig;

class MailConfigService
{
    /**
     * Create user mail config
     * @param User $user
     * @param array $data
     * 
     * @return UserMailConfig $config
     */
    public function create(User $user, array $data)
    {
        UserMailConfig::create([
            'user_id' => $user->id,
            // 'mail_driver' => data_get($data, 'mail_driver'),
            'mail_host' => data_get($data, 'mail_host'),
            'mail_port' => data_get($data, 'mail_port'),
            'mail_username' => data_get($data, 'mail_username'),
            'mail_password' => data_get($data, 'mail_password'),
            'mail_encription' => data_get($data, 'mail_encription'),
            'num_mail' => 0,
            // 'column_name' => data_get($data, 'column_name'),
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
    }

    /**
     * Edit user mail config
     * @param UserMailConfig $config
     * @param User $user
     * @param array $data
     * 
     * @return UserMailConfig $config
     */
    public function update(UserMailConfig $config, User $user, array $data)
    {
        $config->update([
            // 'mail_driver' => data_get($data, 'mail_driver'),
            'mail_host' => data_get($data, 'mail_host'),
            'mail_port' => data_get($data, 'mail_port'),
            'mail_username' => data_get($data, 'mail_username'),
            'mail_password' => data_get($data, 'mail_password'),
            'mail_encription' => data_get($data, 'mail_encription'),
            // 'num_mail' => data_get($data, 'num_mail'),
            // 'column_name' => data_get($data, 'column_name'),
            'updated_at' => time(),
            'updated_by' => $user->id
        ]);
    }
}

