<?php

namespace App\Policies;

use App\Models\MailBox;
use App\Models\User;
use App\Traits\Policies\BasePolicyTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class MailBoxPolicy
{
    use HandlesAuthorization;
    use BasePolicyTrait;

    /**
     * Determine whether the user can pin/unpin mail.
     * @param  \App\Models\User  $user
     * @param  MailBox $mail
     * 
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function pin(User $user, MailBox $mail)
    {
        return $user->id == $mail->user_id
            ? true
            : $this->unauthorizedMessage();
    }
}
