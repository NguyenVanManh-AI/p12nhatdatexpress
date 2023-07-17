<?php

namespace App\Services;

use App\Models\News;
use App\Models\User;

class FocusService
{
    /**
     * like news
     * @param News $news
     * @param User $user
     *
     * @return $result
     */
    public function like(News $news, User $user)
    {
        $result = $news->likes()->toggle($user->id);

        $liked = count($result['attached']) > 0;

        $news->update([
            'num_like' => $liked
                        ? $news->num_like + 1
                        : $news->num_like - 1
        ]);

        return $liked;
    }
}
