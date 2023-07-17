<?php

namespace App\Policies\Classified;

use App\Models\Classified\ClassifiedComment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassifiedCommentPolicy
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
     * @param  ClassifiedComment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ClassifiedComment $comment)
    {
        return $comment->user_id == $user->id && $comment->classified && $comment->classified->isShow()
            ? true
            : $this->deny($this->message);
    }

    /**
     * Determine whether the user can update comment.
     *
     * @param  \App\Models\User  $user
     * @param  ClassifiedComment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function like(User $user, ClassifiedComment $comment)
    {
        return $comment->classified && $comment->classified->isShow()
            ? true
            : $this->deny($this->message);
    }
}
