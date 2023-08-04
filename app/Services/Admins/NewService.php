<?php

namespace App\Services\Admins;

use App\CPU\HelperImage;
use App\Models\Group;
use App\Models\News;
use App\Services\GroupService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewService
{
    /**
     * get all news
     * @param array $queries = []
     *
     * @return $news
     */
    public function index(array $queries = [])
    {
        $itemsPerPage = (int) data_get($queries, 'items') ?: 10;
        $page = (int) data_get($queries, 'page') ?: 1;

        $listScope = data_get($queries, 'request_list_scope');
        $roleId = data_get($queries, 'admin_rol_id');
        $adminId = data_get($queries, 'admin_id');
        $filters = [
            'keyword' => data_get($queries, 'keyword'),
            'status' => data_get($queries, 'is_active'),
            'trashed' => data_get($queries, 'trashed'),
        ];

        $newType = data_get($queries, 'type');

        $news = News::select('news.*')
            ->with('group', 'admin')
            ->filter($filters)
            ->when($listScope, function ($query, $listScope) use ($roleId, $adminId) {
                switch ($listScope) {
                    case 2:
                        return $query->join('admin', 'news.created_by', '=', 'admin.id')
                            ->where('admin.rol_id', $roleId);
                        break;
                    case 3:
                        return $query->where('news.created_by', $adminId);
                        break;
                    default:
                        return $query;
                        break;
                }
            })
            ->when(data_get($queries, 'author_id'), function($query, $authorId) {
                return $query->where('news.created_by', $authorId);
            })
            ->when(data_get($queries, 'group_id'), function($query, $groupId) {
                return $query->where(function ($q) use ($groupId) {
                    return $q->where('news.group_id', $groupId)
                        ->orWhereHas('group', function ($q) use ($groupId) {
                            return $q->where('parent_id', $groupId);
                        });
                });
            })
            ->when($newType != null, function($query) use ($newType) {
                switch ($newType) {
                    case '0': // tin thuong
                        $query->selectRaw('(CASE WHEN (news.is_highlight = 1 AND news.highlight_end > NOW()) OR (news.is_express = 1 AND news.express_end > NOW()) THEN 0 ELSE 1 END ) as normal')
                            ->having('normal', '1');
                        break;
                    case '1': // noi bat
                        $query->highlight();
                        break;
                    case '2': // quang cao
                        $query->ads();
                        break;
                    default:
                        break;
                }

                return $query;
            })
            ->when(data_get($queries, 'start_day'), function($query, $startDay) {
                $start = Carbon::parse($startDay)->startOfDay()->timestamp;
                return $query->where('news.created_at', '>=', $start);
            })
            ->when(data_get($queries, 'end_day'), function($query, $endDay) {
                $end = Carbon::parse($endDay)->endOfDay()->timestamp;
                return $query->where('news.created_at', '<=', $end);
            })
            // ->having('normalss', '1')
            ->latest('news.id')
            ->groupBy('news.id')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $news;
    }

    /**
     * Get form params
     * @return array $params
     */
    public function getFormParams()
    {
        $statuses = [
            [
                'value' => '0',
                'label' => 'Ẩn',
            ],
            [
                'value' => '1',
                'label' => 'Hiện',
            ]
        ];
        $params = [
            'statuses' => $statuses,
            'groups' => $this->getValidGroups(),
        ];

        return $params;
    }

    /**
     * Create new new
     * @param $request
     *
     * @return News $new
     */
    public function create($request)
    {
        // old should check change
        $datas = [
            'group_id' => $request->group_id,
            'news_title' => $request->news_title,
            'news_description' => $request->news_description,
            'news_content' => $request->news_content,
            'news_url' => $request->meta_url,
            'video_url' => $request->video_url,
            'tag_list' => $request->news_tag,
            'meta_title' => $request->meta_title,
            'meta_key' => $request->meta_key,
            'meta_desc' => $request->meta_desc,
            'created_at' => time(),
            'created_by' => Auth::guard('admin')->id(),
            'image_url' => $request->image_url,
            'renew_at' => now(),
            'is_show' => true,
        ];

        if($request->hasFile('audio_url')) {
            $datas["audio_url"] = HelperImage::saveImage('system/audio/news', $request->file('audio_url'));
        }

        // Làm nổi bật tiêu điểm
        if($request->has('checked_hightlight')){
            $datas['is_highlight'] = 1;
            $datas['highlight_start'] = now()->startOfDay();
            $datas['highlight_end'] = now()->endOfDay();
        }

        // Quảng cáo tiêu điểm
        if($request->has('checked_express')){
            $datas['is_express'] = 1;
            $datas['express_start'] = now()->startOfDay();
            $datas['express_end'] = now()->endOfDay();
        }

        News::create($datas);
    }

    /**
     * Update new
     * @param News $new
     * @param $request
     *
     * @return News $new
     */
    public function update(News $new, $request)
    {
        $datas = [
            'group_id' => $request->group_id,
            'news_title' => $request->news_title,
            'news_description' => $request->news_description,
            'news_content' => $request->news_content,
            'video_url' => $request->video_url,
            'tag_list' => $request->news_tag,
            'image_url' => $request->image_url,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->user()->id,
            // SEO
            'meta_key' => $request->meta_key,
            'meta_title' => $request->meta_title,
            'meta_desc' => $request->meta_desc,
            'news_url' => $request->meta_url,
        ];

        if($request->hasFile('audio_url')) {
            $datas["audio_url"] = HelperImage::updateImage('system/audio/news', $request->file('audio_url'), $new->audio_url);
        }

        $new->update($datas);
    }

    /**
     * Create/Update new
     * @param $model
     *
     * @return void
     */
    public function createOrUpdate($model) : void
    {
        $model->news()->updateOrCreate([], [
            'is_active' => true,
        ])->increment('views');
    }

    /**
     * Get valid groups
     * @param $groupId = 47 // tieu diem
     *
     * @return $groups
     */
    public function getValidGroups($groupId = 47)
    {
        $groups = new Collection([]);
        $focusGroup = (new GroupService)->getChildren($groupId);
        $groups = $groups->merge($focusGroup);

        // $parent_group = new Collection();
        // $parent_group = (new GroupService)->prepareData($groups);
        // $groups = $parent_group;

        return $groups->filter(function ($group) {
            // except su kien noi bat
            return $group->id != 48;
        });
    }
}
