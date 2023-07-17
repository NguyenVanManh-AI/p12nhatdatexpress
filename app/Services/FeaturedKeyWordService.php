<?php

namespace App\Services;

use App\Models\District;
use App\Models\FeaturedKeyword;
use App\Models\Group;

class FeaturedKeyWordService
{
    /**
     * get all keywords
     * @param array $queries = []
     *
     * @return $keywords
     */
    public function index(array $queries = [])
    {
        $itemsPerPage = (int) data_get($queries, 'items') ?: 10;
        $page = (int) data_get($queries, 'page') ?: 1;

        $filters = [
            'keyword' => data_get($queries, 'keyword'),
            'status' => data_get($queries, 'is_active'),
            'trashed' => data_get($queries, 'trashed'),
        ];

        $featuredKeywords = FeaturedKeyword::select('featured_keywords.*')
            // ->with('featuredable') // n + 1 but get null morphto try fix by use Ramsey\Uuid\Uuid::uuid6() instead
            ->filter($filters)
            ->when(data_get($queries, 'target_type') != null, function ($query) use ($queries) {
                $query->where('target_type', data_get($queries, 'target_type'));
            })
            ->latest('id')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $featuredKeywords;
    }

    /**
     * Get form params
     * @return array $params
     */
    public function getFormParams()
    {
        $types = [
            [
                'value' => Group::class,
                'label' => 'Mô hình',
            ],
            [
                'value' => District::class,
                'label' => 'Quận/Huyện',
            ]
        ];

        // mô hình tìm kiếm nha-dat-ban 2, nha-dat-cho-thue 10, can-mua-can-thue 18, du-an 34
        $searchParents = [2, 10, 18, 34];
        $paradigms = Group::select('id', 'group_name')
            ->with('children.children')
            ->whereIn('id', $searchParents)
            ->get();

        $provinces = get_cache_province();

        $statuses = [
            [
                'value' => 0,
                'label' => 'Ẩn',
            ],
            [
                'value' => 1,
                'label' => 'Hiện',
            ]
        ];

        $params = [
            'types' => $types,
            'paradigms' => $paradigms,
            'provinces' => $provinces,
            'statuses' => $statuses
        ];

        return $params;
    }

    /**
     * Create new featured keyword
     * @param array $data
     *
     * @return FeaturedKeyword $keyword
     */
    public function create($data)
    {
        $targetType = data_get($data, 'target_type');

        switch ($targetType) {
            case District::class:
                $targetId = data_get($data, 'district_id');
                break;
            case Group::class:
                $targetId = data_get($data, 'paradigm_id');
                break;
            default:
                $targetId = null;
                break;
        }

        FeaturedKeyword::create([
            'target_type' => $targetType,
            'target_id' => $targetId,
            'views' => data_get($data, 'views', 0),
            'is_active' => data_get($data, 'is_active') ? true : false,
        ]);
    }

    /**
     * Update featured keyword
     * @param FeaturedKeyword $keyword
     * @param array $data
     *
     * @return FeaturedKeyword $keyword
     */
    public function update(FeaturedKeyword $keyword, $data)
    {
        $targetType = data_get($data, 'target_type');

        switch ($targetType) {
            case District::class:
                $targetId = data_get($data, 'district_id');
                break;
            case Group::class:
                $targetId = data_get($data, 'paradigm_id');
                break;
            default:
                $targetId = null;
                break;
        }

        $keyword->update([
            'target_type' => $targetType,
            'target_id' => $targetId,
            'views' => data_get($data, 'views', 0),
            'is_active' => data_get($data, 'is_active') ? 1 : 0,
        ]);
    }

    /**
     * Create/Update featured keyword
     * @param $model
     *
     * @return void
     */
    public function createOrUpdate($model) : void
    {
        $model->featuredKeywords()->updateOrCreate([], [
            'is_active' => true,
        ])->increment('views');
    }
}

