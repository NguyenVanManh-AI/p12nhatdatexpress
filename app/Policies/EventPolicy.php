<?php

namespace App\Policies;

use App\Models\Event\Event;
use App\Models\User;
use App\Traits\Policies\BasePolicyTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;
    use BasePolicyTrait;

    /**
     * Determine whether the user can highlight.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function highlight(User $user, Event  $event)
    {
        return $user->id == $event->user_id && $event->canHighLight()
            ? true
            : $this->unauthorizedMessage();
    }

    public function edit(User $user, Event  $event)
    {
        return $user->id == $event->user_id
            ? true
            : $this->unauthorizedMessage();
    }
}
