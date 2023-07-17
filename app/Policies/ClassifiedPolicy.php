<?php

namespace App\Policies;

use App\Models\Classified\Classified;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassifiedPolicy
{
    use HandlesAuthorization;

    private $message;

    public function __construct()
    {
        $this->message = getUnauthorizedMessage();
    }

    /**
     * Determine whether the user can renew.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Classified\Classified  $classified
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function renew(User $user, Classified  $classified)
    {
        return $user->id == $classified->user_id && $classified->canRenew()
            ? true
            : $this->deny($this->message);
    }

    /**
     * Determine whether the user can upgrade vip.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Classified\Classified  $classified
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function upgrade($user, Classified  $classified)
    {
        return $user->id == $classified->user_id && $classified->canUpgrade()
            ? true
            : $this->deny($this->message);
    }

    /**
     * Determine whether the user can create comment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Classified\Classified  $classified
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function comment(User $user, Classified $classified)
    {
        return $classified->isShow() ? true : $this->deny($this->message);
    }
}
