<?php

namespace App\Services;

use App\Models\Province;

class ProvinceService
{
    /**
     * get province from province_url
     *
     * @param string $provinceUrl
     *
     * @return $province
     */
    public function getProvinceFromUrl($provinceUrl)
    {
        $province = Province::where('is_show', 1)
            ->firstWhere('province_url', $provinceUrl);

        return $province;
    }
}
