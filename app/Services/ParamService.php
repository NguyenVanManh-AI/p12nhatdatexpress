<?php

namespace App\Services;

use App\Models\Classified\ClassifiedParam;
use App\Models\Group;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;

class ParamService
{
    /**
     * Get all group children
     * @param string|integer $id
     *
     * @return Collection|null $children
     */
    public function getGroupChildren($id)
    {
        $group = Group::find($id);
        if (!$group) return null;

        $children = null;
        $children = $group->children()->get();

        foreach ($children as $item) {
            $item->children = $this->getGroupChildren($item->id);
        }

        return $children;
    }

    /**
     * Get all group unit price list
     * @param string|integer $id
     *
     * @return Collection|null $unitPrices
     */
    public function getGroupUnitPrice($id)
    {
        $unitPriceMap = [
            '2' =>  [
                1, 3, 4
            ],
            '10' => [
                3, 4, 5, 6
            ],
            '19' => [
                1, 3, 4
            ],
            '20' => [
                3, 4, 5, 6
            ]
        ];

        $unitPriceIds = $id ? data_get($unitPriceMap, $id, []) : [];

        $unitPrices = Unit::select('id', 'unit_name')
            ->whereIn('id', $unitPriceIds)
            ->get();

        return $unitPrices;
    }

    /**
     * Get params by types
     * @param array $types = []
     *
     * @return $params
     */
    public function getClassifiedParamsByTypes($types): Collection
    {
        $params = ClassifiedParam::select('id', 'param_name', 'param_type')
            ->whereIn('param_type', $types)
            ->get();

        return $params;
    }
}
