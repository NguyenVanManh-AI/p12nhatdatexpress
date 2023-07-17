<?php

namespace App\View\Components\News\Home;

use App\Models\Banner\Banner as BannerModel;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Banner extends Component
{
    public $banner_left;
    public $banner_right;
    public $group_id;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->getBanner();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news.home.banner');
    }

    /**
     * Get Banner
     * @return void
     */
    public function getBanner()
    {
        $currentPath = request()->path();

        foreach (Group::select('id', 'group_url', 'parent_id')->get() as $item) {
            if ($currentPath == $item->getTwoLevelGroupUrl()) {
                if ($item->id == 1) break; // get home banner below

                $this->group_id = $item->id;
                $parentGroupIds = $item->getParentGroupIds();

                $ids = implode(',', $parentGroupIds);

                $banner = BannerModel::select('banner.*')
                    ->selectRaw("CASE WHEN banner.user_id IS NULL THEN 1 ELSE 0 END as added_by_admin")
                    ->leftJoin('banner_group', 'banner_group.id', '=', 'banner.banner_group_id')
                    ->where('banner_group.banner_group', 'C')
                    ->whereIn('banner.group_id', $parentGroupIds)
                    ->showed()
                    ->orderByRaw(DB::raw("FIELD(group_id, $ids)")) // get children category first
                    ->latest('added_by_admin') // then get added by admin
                    ->latest('banner.id'); // get latest added | should change

                $bannerLeft = clone $banner;
                $bannerRight = clone $banner;

                $this->banner_left = $bannerRight->where('banner_group.banner_position', 'L')
                    ->first();
                $this->banner_right = $bannerLeft->where('banner_group.banner_position', 'R')
                    ->first();

                break;
            }
        }

        // get home banner if not have category banner
        if (!$this->banner_left || $this->banner_right) {
            $homeBanner = BannerModel::select('banner.*')
                ->leftJoin('banner_group', 'banner_group.id', '=', 'banner.banner_group_id')
                ->where('banner_group.banner_group', 'H')
                ->where('group_id', 1) // home group_id = 1
                ->showed()
                ->latest('banner.id'); // get latest added | should change

            if (!$this->banner_left) {
                $homeLeftBanner = clone $homeBanner;
                $this->banner_left = $homeLeftBanner->where('banner_group.banner_position', 'L')
                    ->first();
            }

            if (!$this->banner_right) {
                $homeRightBanner = clone $homeBanner;
                $this->banner_right = $homeRightBanner->where('banner_group.banner_position', 'R')
                    ->first();
            }
        }
    }
}
