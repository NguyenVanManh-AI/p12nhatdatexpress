<?php

namespace App\Observers;

use App\Enums\UserEnum;
use App\Models\User\UserPostReport;
use App\Services\UserService;

class UserPostReportObserver
{
    private UserService $userService;

    /**
     * inject UserService into BusinessController
     */
    public function __construct()
    {
        $this->userService = new UserService;
    }

    /**
     * Handle the UserPostReport "created" event.
     *
     * @param  \App\Models\UserPostReport  $userPostReport
     * @return void
     */
    public function created(UserPostReport $userPostReport)
    {
        if ($userPostReport->user_post) {
            if ($userPostReport->user_post->reports()->active()->count() >= UserEnum::NUMBER_REPORT) {
                $user_post = $userPostReport->user_post;
                $user_post->is_show = 0;
                $user_post->save();
            }
        }

        if ($userPostReport->user_post_comment) {
            if ($userPostReport->user_post_comment->reports()->active()->count() >= UserEnum::NUMBER_REPORT) {
                $user_post_comment = $userPostReport->user_post_comment;
                $user_post_comment->is_show = 0;
                $user_post_comment->save();
            }
        }

        if ($userPostReport->personal) {
            if ($userPostReport->personal->reports()->active()->count() >= UserEnum::NUMBER_REPORT) {
                $this->userService->blockUser($userPostReport->personal);
            }
        }
    }
}
