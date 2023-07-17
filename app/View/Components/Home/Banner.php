<?php

namespace App\View\Components\Home;

use App\Models\Banner\Banner as BannerModel;
use App\Models\Group;
// use Illuminate\Support\Facades\DB;
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
        $this->getCategoryBanner();
        // $this->getBanner();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.banner');
    }

    /**
     * Lấy banner của chính xác chuyên mục đang truy cập
     * 
     * @return void
     */
    public function getCategoryBanner()
    {
        $currentPath = request()->path();

       

        // dd($parentPath);

        $groups = Group::select('id', 'group_url', 'parent_id')
            ->with('parent.parent')
            ->get();

        // lấy chính xác chuyên mục đang truy cập.
        $group = null;

        foreach ($groups as $item) {
            if ($currentPath == $item->getTwoLevelGroupUrl()) {
                $group = $item;
                break;
            }
        }

        // nếu không tìm ra chuyên mục thì lấy chuyên mục cha cho trang chi tiết
        if (!$group) {
            $pathArr = explode('/', $currentPath);
            $detailParentPath = isset($pathArr[0]) ? $pathArr[0] : null;
            $group = Group::select('id', 'group_url', 'parent_id')
                ->whereIn('group_url', [$detailParentPath, 'trang-chu']) // nếu không có chuyên mục nào khớp thì lấy của trang chủ
                ->whereNull('parent_id')
                ->latest('id')
                ->first();
        }

        if (!$group) return;

        // Lấy banner theo chuyên mục
        $banner = BannerModel::select('banner.*')
            ->selectRaw("CASE WHEN banner.user_id IS NULL THEN 1 ELSE 0 END as added_by_admin")
            ->leftJoin('banner_group', 'banner_group.id', '=', 'banner.banner_group_id')
            ->whereIn('banner_group.banner_group', ['C', 'H']) // trang chủ và chuyên mục
            ->where('banner.group_id', $group->id)
            ->showed()
            ->latest('added_by_admin') // then get added by admin
            ->latest('banner.id'); // get latest added | should change

        $bannerLeft = clone $banner;
        $bannerRight = clone $banner;

        $this->banner_left = $bannerRight->where('banner_group.banner_position', 'L')
            ->first();
        $this->banner_right = $bannerLeft->where('banner_group.banner_position', 'R')
            ->first();
    }

    // /**
    //  * Lấy banner theo chuyên mục. nếu không có thì lấy của chuyên mục cha, không có nữa thì lấy của trang chủ
    //  * 
    //  * @return void
    //  */
    // public function getBanner()
    // {
    //     $currentPath = request()->path();

    //     $groups = Group::select('id', 'group_url', 'parent_id')
    //         ->with('parent.parent')
    //         ->get();

    //     foreach ($groups as $item) {
    //         if ($currentPath == $item->getTwoLevelGroupUrl()) {
    //             if ($item->id == 1) break; // get home banner below

    //             $this->group_id = $item->id;
    //             $parentGroupIds = $item->getParentGroupIds();

    //             $ids = implode(',', $parentGroupIds);

    //             $banner = BannerModel::select('banner.*')
    //                 ->selectRaw("CASE WHEN banner.user_id IS NULL THEN 1 ELSE 0 END as added_by_admin")
    //                 ->leftJoin('banner_group', 'banner_group.id', '=', 'banner.banner_group_id')
    //                 ->where('banner_group.banner_group', 'C')
    //                 ->whereIn('banner.group_id', $parentGroupIds)
    //                 ->showed()
    //                 ->orderByRaw(DB::raw("FIELD(group_id, $ids)")) // get children category first
    //                 ->latest('added_by_admin') // then get added by admin
    //                 ->latest('banner.id'); // get latest added | should change

    //             $bannerLeft = clone $banner;
    //             $bannerRight = clone $banner;

    //             $this->banner_left = $bannerRight->where('banner_group.banner_position', 'L')
    //                 ->first();
    //             $this->banner_right = $bannerLeft->where('banner_group.banner_position', 'R')
    //                 ->first();

    //             break;
    //         }
    //     }

    //     // get home banner if not have category banner
    //     if (!$this->banner_left || $this->banner_right) {
    //         $homeBanner = BannerModel::select('banner.*')
    //             ->leftJoin('banner_group', 'banner_group.id', '=', 'banner.banner_group_id')
    //             ->where('banner_group.banner_group', 'H')
    //             ->where('group_id', 1) // home group_id = 1
    //             ->showed()
    //             ->latest('banner.id'); // get latest added | should change

    //         if (!$this->banner_left) {
    //             $homeLeftBanner = clone $homeBanner;
    //             $this->banner_left = $homeLeftBanner->where('banner_group.banner_position', 'L')
    //                 ->first();
    //         }

    //         if (!$this->banner_right) {
    //             $homeRightBanner = clone $homeBanner;
    //             $this->banner_right = $homeRightBanner->where('banner_group.banner_position', 'R')
    //                 ->first();
    //         }
    //     }
    // }
}
