<?php

namespace App\Services;

use App\Models\Banner\Banner;
use App\Models\Banner\BannerGroup;
use App\Models\Group;

class BannerService
{
    /**
     * Get banner price data for user create express
     * @param array $data
     * 
     * @return array|null
     */
    public function getGroupPriceData($data)
    {
        $isChild = false;

        if (data_get($data, 'banner_group') == 'C') {
            $paradigm = Group::find(data_get($data, 'paradigm_id'));
            $isChild = $paradigm ? true : false;
        }

        $bannerData = BannerGroup::select(
                'id', 'banner_type', 'banner_position', 'banner_group', 'banner_coin_price',
                'banner_height', 'banner_width', 'banner_name'
            )
            ->where('banner_permission', 1)
            ->where('banner_type', data_get($data, 'banner_type'))
            ->where('banner_group', data_get($data, 'banner_group'))
            ->where('is_child', $isChild)
            ->firstWhere('banner_position', data_get($data, 'banner_position'));
        
        return $bannerData;
    }

    /**
     * Create banner
     * @param array $data
     * 
     * @return void
     */
    public function create($data) : void
    {
        Banner::create([
            'banner_group_id' => data_get($data, 'banner_group_id'),
            'group_id' => data_get($data, 'group_id'),
            'image_url' => data_get($data, 'image_url'),
            'date_from' => data_get($data, 'date_from'),
            'date_to' => data_get($data, 'date_to'),
            'is_confirm' => data_get($data, 'is_confirm') ? 1 : 0,
            'user_id' => data_get($data, 'user_id'),
            'transaction_id' => data_get($data, 'transaction_id'),
            'created_at' => time(),
            'created_by' => data_get($data, 'user_id'),
            'link' => data_get($data, 'link'),
            'target_type' => data_get($data, 'target_type') ? true : false,
        ]);
    }
}

