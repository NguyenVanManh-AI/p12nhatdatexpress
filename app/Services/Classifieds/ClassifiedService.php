<?php

namespace App\Services\Classifieds;

use App\CPU\ServiceFee;
use App\Helpers\Helper;
use App\Helpers\SystemConfig;
use App\Models\AdminConfig;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedComment;
use App\Models\Classified\ClassifiedRating;
use App\Models\District;
use App\Models\HomeConfig;
use App\Models\Project;
use App\Models\User;
use App\Services\FeaturedKeyWordService;
use App\Services\GroupService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ClassifiedService
{
    private FeaturedKeyWordService $featuredKeyWordService;
    private UserService $userService;

    public function __construct()
    {
        $this->featuredKeyWordService = new FeaturedKeyWordService;
        $this->userService = new UserService;
    }

    /**
     * get classified list from query
     * @param array $queries
     *
     * @return $classifieds
     */
    public function getListFromQuery(array $queries)
    {
        $paginate = AdminConfig::where('config_code', 'C001')->pluck('config_value')->first();
        $itemsPerPage = $paginate ?? 20;
        $page = (int) data_get($queries, 'page') ?: 1;

        $keyword = data_get($queries, 'keyword');
        if ($keyword)
            Helper::check_keyword($keyword, 1);

        $searchTitle = data_get($queries, 'search_title');

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

        if (request()->load_individual) {
            $provinceLocation = request()->accept_near
                ? getSessionLocation('province', true)
                : null;
        } else {
            $provinceLocation = getSessionLocation('province');
        }

        // should check maybe accept location not search selected province & district ?
        // if ($districtLocation) {
        //     unset($filters['province_id']);
        //     unset($filters['district_id']);
        // }

        $classifieds = Classified::with('location.province', 'location.district', 'unit_price', 'group.parent', 'unit_area', 'progress', 'direction', 'juridical', 'furniture', 'people', 'bed', 'toilet', 'advance', 'ratings')
            ->select(
                'classified.*',
                DB::raw('
                    (
                        CASE
                            WHEN classified.price_unit_id = 1
                                THEN classified.classified_price * classified.classified_area
                            WHEN classified.price_unit_id = 2
                                THEN classified.classified_price * classified.classified_area / 1000
                            WHEN classified.price_unit_id = 4
                                THEN classified.classified_price * 1000
                            WHEN classified.price_unit_id = 6
                                THEN classified.classified_price / 10
                            ELSE classified.classified_price
                        END
                    ) as price_classified'),
                DB::raw("(CASE WHEN (classified.is_hightlight = 1 AND classified.hightlight_end >" . time() . ") THEN 1 WHEN (classified.is_vip = 1 AND classified.vip_end >" . time() . ") THEN 2  ELSE 3 END ) as vip"),
            )
            // convert to million
            // price_unit_id = 1 triệu/m2
            // 2 nghìn/m2
            // 4 tỷ
            // 6 nghìn/tháng
            ->leftJoin('classified_location', 'classified.id', '=', 'classified_location.classified_id')
            ->showed()
            ->when(data_get($queries, 'group_ids'), function ($query, $groupIds) {
                $query->whereIn('classified.group_id', $groupIds);
            })
            ->when($keyword, function ($query, $keyword) {
                // maybe keyword includes search description, location ..
                $query->where('classified.classified_name', 'LIKE', '%' . $keyword . '%');
            })
            ->when($searchTitle, function ($query, $searchTitle) {
                $query->where('classified.classified_name', 'LIKE', '%' . $searchTitle . '%');
            })
            ->when(data_get($queries, 'project'), function ($query, $projectId) {
                $query->where('classified.project_id', $projectId);
            })
            ->when(data_get($queries, 'progress'), function ($query, $progress) {
                $query->where('classified.classified_progress', $progress);
            })
            ->when(data_get($queries, 'direction'), function ($query, $direction) {
                $query->where('classified.classified_direction', $direction);
            })
            ->when(data_get($queries, 'num_bed'), function ($query, $numBed) {
                $query->where('classified.num_bed', $numBed);
            })
            ->when(data_get($queries, 'furniture'), function ($query, $furniture) {
                $query->where('classified.classified_furniture', $furniture);
            })
            ->when(data_get($queries, 'monopoly'), function ($query) {
                $query->where('classified.is_monopoly', 1);
            })
            ->when(data_get($queries, 'broker'), function ($query) {
                $query->where('classified.is_broker', 1);
            })
            ->when(data_get($queries, 'num_people'), function ($query, $numPeople) {
                $query->where('classified.num_people', $numPeople);
            })
            ->when(data_get($queries, 'advance_value'), function ($query, $advanceValue) {
                $query->where('classified.advance_stake', $advanceValue);
            })
            ->when(data_get($queries, 'internet'), function ($query, $internet) {
                $query->where('classified.is_internet', $internet);
            })
            ->when(data_get($queries, 'freezer'), function ($query, $freezer) {
                $query->where('classified.is_freezer', $freezer);
            })
            ->when(data_get($queries, 'balcony'), function ($query, $balcony) {
                $query->where('classified.is_balcony', $balcony);
            })
            ->when(data_get($queries, 'mezzanino'), function ($query, $mezzanino) {
                $query->where('classified.is_mezzanino', $mezzanino);
            })
            //   maybe accept location not search selected province & district ?
            ->when(data_get($queries, 'province_id'), function ($query, $provinceId) {
                $query->where('classified_location.province_id', $provinceId);
            })
            ->when($districtId, function ($query, $districtId) {
                $query->where('classified_location.district_id', $districtId);
            })
            ->when($provinceLocation, function ($query) {
                return $this->selectNear($query);
            });
            // ->when($districtLocation, function ($query, $districtLocation) {
            //     return $query->where('classified_location.district_id', $districtLocation);
            // });

        $areaRange = is_string(data_get($queries, 'area')) ? data_get($queries, 'area') : '';

        if ($areaRange) {
            $areaArr = explode('-', $areaRange);
            $areaFrom = (int) data_get($areaArr, '0');
            $areaEnd = (int) data_get($areaArr, '1');

            if ($areaEnd == 0) {
                $classifieds = $classifieds->where('classified_area', '>=' . $areaFrom);
            } else {
                $classifieds = $classifieds->whereBetWeen('classified_area', $areaArr);
            }
        }

        $category = data_get($queries, 'category');
        $priceRange = data_get($queries, 'price');

        if ($priceRange) {
            $priceArr = explode('-', $priceRange);
            $priceFrom = (int) data_get($priceArr, '0');
            $priceEnd = (int) data_get($priceArr, '1');

            if ($category != 'nha-dat-cho-thue' && $category != 'can-thue') {
                // convert from billion to million price
                $priceFrom = $priceFrom * 1000;
                $priceEnd = $priceEnd * 1000;
            }

            if ($priceEnd == 0) {
                $classifieds = $classifieds->havingRaw("price_classified >={$priceFrom}");
            } else {
                $classifieds = $classifieds->havingRaw("price_classified >={$priceFrom}");
                $classifieds = $classifieds->havingRaw("price_classified <={$priceEnd}");
            }
        }

        // sort
        $sortFilter = [
            'luot-xem-nhieu-nhat' => ['classified.num_view', 'DESC'],
            'gia-cao-nhat' => ['price_classified', 'DESC'], // classified.classified_price ?
            'gia-thap-nhat' => ['price_classified', 'ASC'],
            'dien-tich-lon-nhat' => ['classified.classified_area', 'DESC'],
            'dien-tich-nho-nhat' => ['classified.classified_area', 'ASC'],
        ];
        $sort = data_get($queries, 'sort');

        if (array_key_exists($sort, $sortFilter)) {
            $classifieds = $classifieds->orderBy(data_get($sortFilter[$sort], '0', 'renew_at'), data_get($sortFilter[$sort], '1', 'DESC'));
        } else {
            // default sorting
            $classifieds = $classifieds->oldest('vip') // sort vip first
                ->latest('classified.renew_at');
        }

        $path = request()->path();
        $path = str_replace('search-classified/', '', $path);
        $path = str_replace('vi-tri/', '', $path);

        $getAll = data_get($queries, 'get_all');

        if ($getAll) {
            $classifieds = $classifieds->get();
        } else {
            $classifieds = $classifieds->skip(($page - 1) * $itemsPerPage)
                ->paginate($itemsPerPage)
                ->withPath($path);
        }

        return $classifieds;
    }

    /**
     * create rating
     * @param Classified $classified
     * @param array $data
     *
     * @return ClassifiedRating $rating
     */
    public function createRating(Classified $classified, $data)
    {
        $userId = data_get($data, 'user_id');
        $uniqueCondition = [
            'classified_id' => $classified->id,
        ];

        $userId
            ? $uniqueCondition['user_id'] = $userId
            : $uniqueCondition['ip'] = data_get($data, 'ip');

        $rating = $classified->ratings()
            ->updateOrCreate($uniqueCondition, [
                'star' => data_get($data, 'star'),
                'rating_time' => time(),
            ]);

        return $rating;
    }

    /**
     * get estate news for home page
     * @param array $queries
     *
     * @return $estateNews
     */
    public function getEstateNews($queries = [])
    {
        $classifiedConfig = HomeConfig::select('num_classified')->first();
        $itemsPerPage = data_get($classifiedConfig, 'num_classified', 20);
        $page = data_get($queries, 'page', 1);

        $estateNews = Classified::with(
                'unit_price:id,unit_name',
                'unit_area:id,unit_name',
                'location.province:id,province_name,province_type,province_url',
                'location.district:id,district_name,district_type,province_id,district_url',
                'direction:id,direction_name',
                'project',
                'bed',
                'toilet',
                'juridical',
                'people',
                'advance',
                'group',
                'progress',
                'furniture',
                'group.parent'
            )
            ->select(
                'classified.*',
                // 'group.id as group_id',
                'group.group_name',
                'group.group_url',
                DB::raw("(CASE WHEN (classified.is_hightlight = 1 AND classified.hightlight_end >" . time() . ") THEN 1 WHEN (classified.is_vip = 1 AND classified.vip_end >" . time() . ") THEN 2  ELSE 3 END ) as vip"),
            )
            ->leftJoin('user', 'classified.user_id', '=', 'user.id')
            ->leftJoin('classified_location', 'classified.id', '=', 'classified_location.classified_id')
            ->leftJoin('province', 'province.id', '=', 'classified_location.province_id')
            ->leftJoin('district', 'district.id', '=', 'classified_location.district_id')
            ->leftJoin('group', 'classified.group_id', 'group.id')
            ->leftJoin('group as group_parent', 'group.parent_id', 'group_parent.id')
            ->leftJoin('group as group_parent_parent', 'group_parent.parent_id', 'group_parent_parent.id')
            ->where(function ($query) {
                return $query->whereIn('group_parent.id', [2, 10])
                    ->orWhereIn('group_parent_parent.id', [2, 10]);
            })
            ->when(request()->accept_near, function ($query) {
                return $this->selectNear($query);
            })
            ->showed()
            ->oldest('vip')
            ->latest('classified.renew_at')
            ->latest('classified.id')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $estateNews;
    }

    /**
     * Get relates classifieds
     * @param $data
     *
     * @return $classifieds
     */
    public function getRelates(array $data)
    {
        $projectId = data_get($data, 'project_id');
        $classifiedId = data_get($data, 'classified_id');

        if (!$projectId && !$classifiedId) return null;

        $relatedLimit = config('constants.classified.related.limit', 8);
        $districtId = $classifiedPrice = null;
        $groupIds = [];
        $acceptNear = data_get($data, 'accept_near') ? true : false;
        $loadIndividual = data_get($data, 'load_individual');
        $sameProjects = $sameGroups = $sameDistricts = $samePrices = new Collection();

        if ($loadIndividual) {
            $districtLocation = $acceptNear ? getDistrictLocation(true) : null;
        } else {
            $districtLocation = getDistrictLocation();
        }

        $project = Project::find($projectId);
        if ($project) {
            $group = $project->group;

            // order tin rao theo chuyên mục phụ thuộc từ chuyên mục của dự án.
            if ($group) {
                $groupIds = $group->dependencies->pluck('id')->toArray();
            }

            $districtId = data_get($project->location, 'district_id');
        }

        $classified = Classified::find($classifiedId);
        if ($classified) {
            $projectId = $classified->project_id;
            $groupIds = [$classified->group_id];
            $districtId = data_get($classified->location, 'district_id');
            $classifiedPrice = $classified->classified_price;
        }

        $classifiedQueries = Classified::select(
                'classified.*',
                DB::raw("(CASE WHEN (classified.is_hightlight = 1 AND classified.hightlight_end >".time().") THEN 1 WHEN (classified.is_vip = 1 AND classified.vip_end >".time().") THEN 2  ELSE 3 END ) as vip"),
            )
            ->with('location.province', 'project', 'location.district', 'unit_price', 'group.parent', 'unit_area', 'progress', 'direction', 'juridical', 'furniture', 'people', 'bed', 'toilet', 'advance', 'ratings')
            ->showed()
            ->when($districtLocation, function ($query, $districtLocation) {
                return $query->leftJoin('classified_location', 'classified_location.classified_id', '=', 'classified.id')
                    ->where('classified_location.district_id', $districtLocation);
            })
            ->distinct();

        // orderby same project first
        $sameProjects = clone $classifiedQueries;
        $sameProjects = $sameProjects->when($projectId, function ($query, $projectId) {
                $query->where('classified.project_id', $projectId);
                // $query->orderByRaw(DB::raw('CASE WHEN classified.project_id = ' . $projectId . ' THEN 0 ELSE 1 END'));
            })
            ->oldest('vip')
            ->latest('classified.renew_at')
            ->latest('classified.id')
            ->take($relatedLimit)
            ->get();

        $classifieds = $sameProjects;
        $relatedLimit = $relatedLimit - $classifieds->count();

        if ($relatedLimit > 0) {
            // same group | maybe check same parent group too
            $sameGroups = clone $classifiedQueries;
            $sameGroups = $sameGroups->when($groupIds, function ($query, $groupIds) {
                    $query->whereIn('classified.group_id', $groupIds);
                    // $query->orderByRaw(DB::raw("FIELD(classified.group_id, $groupIds)"));
                })
                ->whereNotIn('classified.id', $classifieds->pluck('id'))
                ->oldest('vip')
                ->latest('classified.renew_at')
                ->latest('classified.id')
                ->take($relatedLimit)
                ->get();

            $classifieds = $classifieds->merge($sameGroups);
            $relatedLimit = $relatedLimit - $classifieds->count();

            if ($relatedLimit > 0) {
                if (!$districtLocation) {
                    // same district
                    $sameDistricts = clone $classifiedQueries;

                    if (!tableJoined($sameDistricts, 'classified_location')) {
                        $sameDistricts = $sameDistricts->leftJoin('classified_location', 'classified_location.classified_id', '=', 'classified.id');
                    }
                    $sameDistricts = $sameDistricts->when($districtId, function ($query, $districtId) {
                            $query->where('classified_location.district_id', $districtId);
                        })
                        ->oldest('vip')
                        ->latest('classified.renew_at')
                        ->latest('classified.id')
                        ->take($relatedLimit)
                        ->get();

                    $classifieds = $classifieds->merge($sameDistricts);
                    $relatedLimit = $relatedLimit - $classifieds->count();
                }

                if ($relatedLimit > 0) {
                    // same price
                    $samePrices = clone $classifiedQueries;
                    $samePrices = $samePrices->when($classifiedPrice, function ($query, $classifiedPrice) {
                            $query->where('classified.classified_price', $classifiedPrice);
                        })
                        ->oldest('vip')
                        ->latest('classified.renew_at')
                        ->latest('classified.id')
                        ->take($relatedLimit)
                        ->get();

                   $classifieds = $classifieds->merge($samePrices);
                }
            }

            // // maybe get classified with no condition
            // if ($relatedLimit > 0) {
            //     $anyClassifieds = clone $classifiedQueries;
            //     $anyClassifieds = $anyClassifieds->whereNotIn('classified.id', $classifieds->pluck('id'))
            //         ->oldest('vip')
            //         ->latest('classified.renew_at')
            //         ->latest('classified.id')
            //         ->take($relatedLimit)
            //         ->get();

            //     $classifieds = $classifieds->merge($anyClassifieds);
            // }
        }

        return $classifieds;
    }

    // /**
    //  * decrease/increase the amount of classified remaining of user package
    //  * @param Classified $classified
    //  * @param string $type = 'decrease'
    //  * @param int $amount = 1
    //  *
    //  * @return void
    //  */
    // public function changeAmountRemaining(Classified $classified, $type = 'decrease', $amount = 1) : void
    // {
    //     $classified->
    // }

    /**
     * Update service or package for classified
     * @param int $classifiedId
     * @param array $data
     *
     * @return mixed|string|void
     */
    public function updatePackageService($classifiedId, array $data)
    {
        $classified = Classified::find($classifiedId);
        $user = $classified->user;
        if (!$classified || !$user) return '';

        $selectedService = data_get($data, 'service');

        // buy vip or highlight with coin | service_fee_id = 2 or 3
        if ($selectedService == 2 || $selectedService == 3) {
            $serviceStatus = ServiceFee::classified_fee($selectedService, $classifiedId);

            if (data_get($serviceStatus, 'status')) {
                if ($selectedService == 2) {
                    // update for vip
                    $classifiedServiceData = [
                        'is_vip' => 1,
                        'vip_begin' => time(),
                        'vip_end' => time() + data_get($serviceStatus, 'service.existence_time')
                    ];
                } else {
                    // update for highlight
                    $classifiedServiceData = [
                        'is_hightlight' => 1,
                        'hightlight_begin' => time(),
                        'hightlight_end' => time() + data_get($serviceStatus, 'service.existence_time')
                    ];
                }

                $classified->update($classifiedServiceData);
            }

            return $serviceStatus['message'];
        }

        // create with package
        $selectedPackage = data_get($data, 'package');
        $packageLists = [1, 2, 3]; // 1 = tin thường, 2 = tin vip, 3 = tin nổi bật
        if (in_array($selectedPackage, $packageLists)) {
            $currentPackage = $this->userService->getCurrentBalance($user);
            $message = 'Đăng tin thành công';

            // should change | tạm thời | admin duyệt mới trừ số tin vip. ko duyệt ko trừ. đổi thành auto duyệt sau
            if ($selectedPackage == 2) {
                $vipPendingCount = $this->userService->getClassifiedPackagePendingCount($user, 'vip');

                if ($currentPackage && $currentPackage->vip_amount > $vipPendingCount) {
                    $vipTime = data_get($currentPackage->selectedPackage, 'vip_duration', SystemConfig::DAY_TIME);

                    // should auto decrease vip amount if use auto check
                    $classifiedServiceData = [
                        'is_vip' => 1,
                        'vip_begin' => time(),
                        'vip_end' => time() + $vipTime,
                    ];

                    $message = 'Đăng tin Vip thành công!';
                } else {
                    $message = 'Không đủ số tin Vip';
                }
            }

            if ($selectedPackage == 3) {
                $highlightPendingCount = $this->userService->getClassifiedPackagePendingCount($user, 'highlight');

                if ($currentPackage && $currentPackage->highlight_amount > $highlightPendingCount) {
                    $hightlightTime = data_get($currentPackage->selectedPackage, 'highlight_duration', SystemConfig::DAY_TIME);

                    // should auto decrease highlight amount if use auto check
                    $classifiedServiceData = [
                        'is_hightlight' => 1,
                        'hightlight_begin' => time(),
                        'hightlight_end' => time() + $hightlightTime,
                    ];

                    $message = 'Đăng tin Nổi bật thành công!';
                } else {
                    $message = 'Không đủ số tin Nổi bật';
                }
            }

            $classifiedServiceData['user_balance_id'] = $currentPackage->id;
            $classified->update($classifiedServiceData);

            return $message;
        }

        return '';
    }

    /**
     * add new comment
     * @param Classified $classified
     * @param array $data
     *
     * @return ClassifiedComment $classifiedComment
     */
    public function addComment(Classified $classified, array $data)
    {
        return $classified->comments()
            ->create([
                'comment_content' => data_get($data, 'content'),
                'user_id' => data_get($data, 'user_id'),
                'parent_id' => data_get($data, 'parent_id'),
                'is_show' => 1,
            ]);
    }

    /**
     * update comment
     * @param ClassifiedComment $comment
     * @param array $data
     *
     * @return ClassifiedComment $comment
     */
    public function updateComment(ClassifiedComment $comment, array $data)
    {
        $comment->update([
                'comment_content' => data_get($data, 'content'),
            ]);

        return $comment;
    }

     /**
     * like comment
     * @param ClassifiedComment $comment
     * @param User $user
     *
     * @return $result
     */
    public function like(ClassifiedComment $comment, User $user)
    {
        return $comment->likes()->toggle($user->id);
    }

    /**
     * create report
     * @param model
     * @param array $data
     *
     * @return $report
     */
    public function createReport($model, array $data)
    {
        // should change all report to morph table for (project, event, comment...)
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
                    point(classified_location.map_longtitude, classified_location.map_latitude),
                    point(?, ?)
                )
            ) < ?", [$lng, $lat, $radius])
            // check only in province
            ->when($provinceLocation, function ($query, $provinceLocation) {
                return $query->where('classified_location.province_id', $provinceLocation);
            })
            ->when($sort, function ($query) use ($lng, $lat) {
                return $query->selectRaw('
                        Round(ST_Distance_Sphere(Point(classified_location.map_longtitude, classified_location.map_latitude),
                        Point(' . $lng . ',' . $lat . '))/1000, 1)
                        as distance'
                    )
                    ->oldest('distance');
            });
            
        // $haversine = "(round(6371 * acos(cos(radians({$lat}))
        //             * cos(radians(classified_location.map_latitude))
        //             * cos(radians(classified_location.map_longtitude)
        //             - radians({$lng}))
        //             + sin(radians({$lat}))
        //             * sin(radians(classified_location.map_latitude)))))";
        // return $query
        //     ->selectRaw("{$haversine} AS distance")
        //     ->having('distance', '>', 0)
        //     ->having('distance', '<', (int) $radius)
        //     ->orderBy('distance');
    }
}
