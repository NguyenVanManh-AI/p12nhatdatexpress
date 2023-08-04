<?php

namespace App\Services;

use App\Enums\NewLikeTypeEnum;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * like news
     * 
     * @param News $new
     * @param User $user
     * @param $type = \App\Enums\NewLikeTypeEnum::LIKE
     * @return bool $toggled
     */
    public function toggleReaction(News $new, User $user, $type = NewLikeTypeEnum::LIKE)
    {
        if ($type == NewLikeTypeEnum::LIKE) {
            $result = $new->likes()
                ->toggle([$user->id => [
                    'type' => NewLikeTypeEnum::LIKE ? true : false
                ]]);
            $new->update([
                'num_like' => $new->likes->count()
            ]);
        } else {
            $result = $new->dislikes()
                ->toggle([$user->id => [
                    'type' => NewLikeTypeEnum::DISLIKE ? true : false
                ]]);
            $new->update([
                'num_dislike' => $new->dislikes->count()
            ]);
        }

        return count($result['attached']) ? true : false;
    }

    /**
     * get focus news lists from query
     * @param array $queries
     *
     * @return $news
     */
    public function getListFromQuery(array $queries)
    {
        $itemsPerPage = (int) data_get($queries, 'items_per_page') ?: 9;
        $page = (int) data_get($queries, 'page') ?: 1;

        $keyword = data_get($queries, 'keyword');
        $groupId = data_get($queries, 'group_id');
        $sortNumView = data_get($queries, 'sort_num_view');
        $startDate = data_get($queries, 'start_date') ? Carbon::parse(data_get($queries, 'start_date'))->timestamp : null;
        $endDate = data_get($queries, 'end_date') ? Carbon::parse(data_get($queries, 'end_date'))->timestamp : null;
        $isAd = data_get($queries, 'is_ad');
        $notIds = is_array(data_get($queries, 'not_ids')) ? data_get($queries, 'not_ids') : [];

        $news = News::select(
                'news.*',
                DB::raw('(CASE WHEN (news.is_highlight = 1 AND news.highlight_end > NOW()) THEN 1 ELSE 0 END ) as highlight')
                // DB::raw('(CASE WHEN (news.is_highlight = 1 AND news.highlight_end > NOW()) THEN 2 WHEN (news.is_express = 1 AND news.express_end > NOW()) THEN 1 ELSE 0 END ) as sort_vip')
            )
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->showed()
            ->when($keyword, function ($query, $keyword) {
                return $query->where('news.news_title', 'LIKE', '%' . $keyword . '%');
            })
            ->when($groupId, function ($query, $groupId) {
                return $query->where('news.group_id', $groupId);
            })
            ->when($sortNumView, function ($query) {
                return $query->latest('news.num_view');
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('news.created_at', [$startDate, $endDate]);
            })
            ->when($isAd, function ($query) {
                return $query->ads();
            })
            ->when(count($notIds), function ($query) use ($notIds) {
                return $query->whereNotIn('news.id', $notIds);
            })
            ->latest('highlight')
            ->latest('news.renew_at')
            ->latest('news.created_at');

        $getAll = data_get($queries, 'get_all');
        $limit = data_get($queries, 'limit');
        $getFirst = data_get($queries, 'get_first');

        if ($getFirst) {
            $news = $news->first();
        } elseif ($getAll || $limit) {
            $news = $news->when($limit, function ($query, $limit) {
                    return $query->limit($limit);
                })
                ->get();
        } else {
            $news = $news->skip(($page - 1) * $itemsPerPage)
                ->paginate($itemsPerPage);
        }

        return $news;
    }
}
