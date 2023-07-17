<?php

namespace App\Services\Admins;

use App\Models\Province;

class SeoService
{
    /**
     * get all provinces
     * @param array $queries = []
     *
     * @return $provinces
     */
    public function getSeoProvinces(array $queries = [])
    {
        $itemsPerPage = (int) data_get($queries, 'items') ?: 10;
        $page = (int) data_get($queries, 'page') ?: 1;

        $filters = [
            'keyword' => data_get($queries, 'keyword'),
        ];

        $provinces = Province::filter($filters)
            ->showed()
            ->oldest('province_name')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $provinces;
    }

    /**
     * update province seo
     * @param Province $province
     * @param array $data
     * 
     * @return Province $province
     */
    public function updateProvince(Province $province, array $data)
    {
        $seo = data_get($province, 'seo') ?: [];

        $seo['meta_key'] = data_get($data, 'seo.meta_key');
        $seo['meta_title'] = data_get($data, 'seo.meta_title');
        $seo['meta_description'] = data_get($data, 'seo.meta_description');

        $province->update([
            'seo' => $seo,
        ]);

        // create log

        return $province;
    }
}
