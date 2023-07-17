<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserMailTemplate;

class UserMailTemplateService
{
    /**
     * update mail
     * @param array $data
     *
     * @return UserMailTemplate $mail
     */
    public function create(User $user, array $data)
    {
        $mail = UserMailTemplate::create([
            'user_id' => $user->id,
            'mail_header' => data_get($data, 'mail_header'),
            'mail_content' => data_get($data, 'mail_content'),
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);

        return $mail;
    }

    /**
     * update mail
     * @param UserMailTemplate $mail
     * @param array $data
     *
     * @return UserMailTemplate $mail
     */
    public function update(UserMailTemplate $mail, User $user, array $data)
    {
        $mail->update([
            'mail_header' => data_get($data, 'mail_header'),
            'mail_content' => data_get($data, 'mail_content'),
            'updated_at' => time(),
            'updated_by' => $user->id
        ]);

        return $mail;
    }
}
