<?php

namespace App\Policies\Project;

use App\Models\ProjectComment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectCommentPolicy
{
    use HandlesAuthorization;
    private $message;

    public function __construct()
    {
        $this->message = getUnauthorizedMessage();
    }

    /**
     * Determine whether the user can update comment.
     *
     * @param  \App\Models\User  $user
     * @param  ProjectComment $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProjectComment $comment)
    {
        return $comment->user_id == $user->id && $comment->project && $comment->project->isShow()
            ? true
            : $this->deny($this->message);
    }

    /**
     * Determine whether the user can update comment.
     *
     * @param  \App\Models\User  $user
     * @param  ProjectComment $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function like(User $user, ProjectComment $comment)
    {
        return $comment->project && $comment->project->isShow()
            ? true
            : $this->deny($this->message);
    }
}
