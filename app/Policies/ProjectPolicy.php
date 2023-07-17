<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    private $message;

    public function __construct()
    {
        $this->message = getUnauthorizedMessage();
    }

    /**
     * Determine whether the user can upgrade vip.
     *
     * @param  User $user
     * @param  Project $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function comment(User $user, Project $project)
    {
        return $project->isShow() ? true : $this->deny($this->message);
    }
}
