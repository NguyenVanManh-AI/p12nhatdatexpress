<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\AdminConfig;
use App\Models\District;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    private FeaturedKeyWordService $featuredKeyWordService;

    public function __construct()
    {
        $this->featuredKeyWordService = new FeaturedKeyWordService;
    }

    /**
     * get project list from query
     * @param array $queries
     *
     * @return $projects
     */
    public function getListFromQuery(array $queries)
    {
        $projects = [];
        $paginate = AdminConfig::where('config_code', 'C004')->pluck('config_value')->first();
        $itemsPerPage = $paginate ?? 20;
        $page = (int) data_get($queries, 'page') ?: 1;

        // create featured keyword
        $districtId = data_get($queries, 'district_id');
        $paradigmUrl = data_get($queries, 'paradigm');
        if ($districtId && $paradigmUrl) {
            $paradigm = (new GroupService())->getGroupFromUrl($paradigmUrl);
            $district = District::showed()->find($districtId);
            if ($paradigm && $district) {
                $this->featuredKeyWordService->createOrUpdate($paradigm);
                $this->featuredKeyWordService->createOrUpdate($district);
            }
        }

        // price_project_real convert to million
        // price_unit_id = 1 triệu/m2
        // 2 nghìn/m2
        // 4 tỷ
        $projects = Project::select(
                'project.*',
                DB::raw('
                    (
                        CASE
                            WHEN project.price_unit_id = 1
                                THEN project.project_price * project.project_area_to
                            WHEN project.price_unit_id = 2
                                THEN project.project_price * project.project_area_to / 1000
                            WHEN project.price_unit_id = 4
                                THEN project.project_price * 1000
                            ELSE project.project_price
                        END
                    ) as price_project_real'
                )
            )
            ->with('location')
            ->leftJoin('project_location', 'project_location.project_id', 'project.id')
            ->showed()
            ->when(data_get($queries, 'group_ids'), function ($query, $groupIds) {
                $query->whereIn('project.group_id', $groupIds);
            });

        $projects = $this->filter($queries, $projects);
        $projects = $this->sort(data_get($queries, 'sort'), $projects);

        $path = request()->path();
        $path = str_replace('search-project', '', $path);

        $take = data_get($queries, 'take');
        if ($take) {
            return $projects->take($take)
                ->get();
        }

        $getAll = data_get($queries, 'get_all');
        if ($getAll) {
            $projects = $projects->get();
        } else {
            $projects = $projects->skip(($page - 1) * $itemsPerPage)
                ->paginate($itemsPerPage)
                ->withPath($path);
        }

        return $projects;
    }

    private function filter($filters, $query)
    {
        $keyword = data_get($filters, 'keyword');
        if ($keyword)
            Helper::check_keyword($keyword, 1);


        if (data_get($filters, 'load_individual')) {
            $provinceLocation = request()->accept_near
                ? getSessionLocation('province', true)
                : null;
        } else {
            $provinceLocation = getSessionLocation('province');
        }

        // $districtLocation = getDistrictLocation();

        // if (data_get($filters, 'load_individual')) {
        //     $districtLocation = data_get($filters, 'accept_near') ? getDistrictLocation(true) : null;
        // } else {
        //     $districtLocation = getDistrictLocation();
        // }

        // should check maybe accept location not search selected province & district ?
        // if ($districtLocation) {
        //     unset($filters['province_id']);
        //     unset($filters['district_id']);
        // }

        $query = $query->when($keyword, function ($query, $keyword) {
                // maybe keyword includes search description, location ..
                $query->where('project.project_name', 'LIKE', '%' . $keyword . '%');
            })
            ->when(data_get($filters, 'search_title'), function ($query, $searchTitle) {
                $query->where('project.project_name', 'LIKE', '%' . $searchTitle . '%');
            })
            ->when(data_get($filters, 'province_id'), function ($query, $provinceId) {
                $query->where('project_location.province_id', $provinceId);
            })
            ->when(data_get($filters, 'district_id'), function ($query, $districtId) {
                $query->where('project_location.district_id', $districtId);
            })
            // ->when($districtLocation, function ($query, $districtLocation) {
            //     return $query->where('project_location.district_id', $districtLocation);
            // })
            ->when(data_get($filters, 'progress_id'), function ($query, $progress) {
                $query->where('project.project_progress', $progress);
            })
            ->when(data_get($filters, 'direction'), function ($query, $direction) {
                $query->where('project.project_direction', $direction);
            })
            ->when(data_get($filters, 'furniture'), function ($query, $furniture) {
                $query->where('project.project_furniture', $furniture);
            })
            ->when(data_get($filters, 'bank_sponsor'), function ($query) {
                $query->where('project.bank_sponsor', 1);
            })
            ->when($provinceLocation, function ($query) {
                return $this->selectNear($query);
            });

        $listUtilityMap = [
            'parking' => 1,
            'gym' => 2,
            'kindergarten' => 3,
            'ecommerce' => 4,
            'park' => 5,
            'spa' => 6,
            'pool' => 9,
        ];

        foreach ($listUtilityMap as $type => $utility) {
            if (data_get($filters, $type)) {
                $query = $query->where('project.list_utility', 'LIKE', '%' . $utility .'%');
            }
        }

        $areaRange = is_string(data_get($filters, 'area')) ? data_get($filters, 'area') : '';

        if ($areaRange) {
            $areaArr = explode('-', $areaRange);
            $areaFrom = (int) data_get($areaArr, '0');
            $areaEnd = (int) data_get($areaArr, '1');

            if ($areaEnd == 0) {
                $query = $query->where('project.project_area_to', '>=' . $areaFrom);
            } else {
                $query = $query->whereBetWeen('project.project_area_to', $areaArr);
            }
        }

        $priceRange = data_get($filters, 'price');

        if ($priceRange) {
            $priceArr = explode('-', $priceRange);
            $priceFrom = (int) data_get($priceArr, '0') * 1000; // convert from billion to million price
            $priceEnd = (int) data_get($priceArr, '1') * 1000;

            if ($priceEnd == 0) {
                $query = $query->havingRaw("price_project_real >={$priceFrom}");
            } else {
                $query = $query->havingRaw("price_project_real >={$priceFrom}");
                $query = $query->havingRaw("price_project_real <={$priceEnd}");
            }
        }

        return $query;
    }

    /**
     * sort project
     * @param string $sort
     * @param $query
     *
     * @return $query
     */
    private function sort($sort, $query)
    {
        // sort
        $sortFilter = [
            'luot-xem-nhieu-nhat' => ['project.num_view', 'DESC'],
            'gia-cao-nhat' => ['price_project_real', 'DESC'],
            'gia-thap-nhat' => ['price_project_real', 'ASC'],
            'dien-tich-lon-nhat' => ['project.project_area_to', 'DESC'],
            'dien-tich-nho-nhat' => ['project.project_area_to', 'ASC'],
        ];

        if (array_key_exists($sort, $sortFilter)) {
            $query = $query->orderBy(data_get($sortFilter[$sort], '0', 'project'), data_get($sortFilter[$sort], '1', 'DESC'));
        } else {
            // default sorting
            $query = $query->latest('project.id');
        }

        return $query;
    }

    /**
     * create rating
     * @param Project $project
     * @param array $data
     *
     * @return ProjectRating $rating
     */
    public function createRating(Project $project, $data)
    {
        $userId = data_get($data, 'user_id');
        $uniqueCondition = [
            'project_id' => $project->id,
        ];

        $userId
            ? $uniqueCondition['user_id'] = $userId
            : $uniqueCondition['ip'] = data_get($data, 'ip');

        $rating = $project->ratings()
            ->updateOrCreate($uniqueCondition, [
                'star' => data_get($data, 'star'),
                'rating_time' => time(),
            ]);

        return $rating;
    }

    /**
     * add new comment
     * @param Project $project
     * @param array $data
     *
     * @return ProjectComment $projectComment
     */
    public function addComment(Project $project, array $data)
    {
        return $project->projectComments()
            ->create([
                'comment_content' => data_get($data, 'content'),
                'user_id' => data_get($data, 'user_id'),
                'parent_id' => data_get($data, 'parent_id'),
                'is_show' => 1,
            ]);
    }

    /**
     * update comment
     * @param ProjectComment $comment
     * @param array $data
     *
     * @return ProjectComment $comment
     */
    public function updateComment(ProjectComment $comment, array $data)
    {
        $comment->update([
                'comment_content' => data_get($data, 'content'),
            ]);

        return $comment;
    }

    /**
     * like comment
     * @param ProjectComment $comment
     * @param User $user
     *
     * @return $result
     */
    public function like(ProjectComment $comment, User $user)
    {
        $result = $comment->likes()->toggle($user->id);

        return $result;
    }

    /**
     * create report
     * @param $model
     * @param array $data
     *
     * @return $report
     */
    public function createReport($model, array $data)
    {
        return $model->reports()
            ->create([
                'user_id' => data_get($data, 'user_id'),
                'report_type' => data_get($data, 'report_type'),
                'report_content' => data_get($data, 'report_content'),
                'report_position' => data_get($data, 'report_position'),
                'report_result' => 1,
                'confirm_status' => 0,
                'report_time' => time(),
            ]);
    }

     /**
     * query search near
     * @param $query
     * @param boolean $sort = false
     * @param int|string $radius = 15 default 15km
     *
     * @return $query
     */
    public function selectNear($query, $sort = false, $radius = 15)
    {
        $provinceLocation = getSessionLocation('province', true);
        $location = getSessionLocation('latLng', true);
        $lat = data_get($location, 'lat');
        $lng = data_get($location, 'lng');

        if ($lat === null || $lng === null) return $query;

        if ($lat < -90 || $lat > 90) $lat = 90;
        if ($lng < -180 || $lng > 180) $lng = 180;

        $radius = (int) $radius * 1000; // convert km to meters

        return $query->whereRaw("(
                ST_Distance_Sphere(
                    point(project_location.map_longtitude, project_location.map_latitude),
                    point(?, ?)
                )
            ) < ?", [$lng, $lat, $radius])
            // check only in province
            ->when($provinceLocation, function ($query, $provinceLocation) {
                return $query->where('project_location.province_id', $provinceLocation);
            })
            ->when($sort, function ($query) use ($lng, $lat) {
                return $query->selectRaw('
                        Round(ST_Distance_Sphere(Point(project_location.map_longtitude, project_location.map_latitude),
                        Point(' . $lng . ',' . $lat . '))/1000, 1)
                        as distance'
                    )
                    ->oldest('distance');
            });
    }
}
