<?php

namespace App\Services\Classifieds;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Collection;

class SearchService
{
    private GroupService $groupService;

    /**
     * inject group service
     */
    public function __construct()
    {
        $this->groupService = new GroupService;
    }

    /**
     * get data for search form
     * @param string $category
     * 
     * @return array $searchData
     */
    public function getFormData($category) : array
    {
        $formData = [
            'paradigms' => [],
            'prices' => [],
            'areas' => [],
        ];

        $group = Group::firstWhere('group_url', $category);

        if ($group) {
            $formData['paradigms'] = $this->getAllChildren($group);
        }

        $categoryFilter = [
            'nha-dat-ban' => 'sell',
            'nha-dat-cho-thue' => 'rent',
            'du-an' => 'project_area',
            'can-mua' => 'sell',
            'can-thue' => 'rent',
        ];

        if (array_key_exists($category, $categoryFilter)) {
            $formData['prices'] = config("constants.classified.search.price.{$categoryFilter[$category]}", []);
            $formData['areas'] = config("constants.classified.search.area.{$categoryFilter[$category]}", []);
        }

        return $formData;
    }

    /**
     * get data of paradigm for search advance
     * @param Group $group
     * 
     * @return array $paradigmData
     */
    public function getParadigmData($group)
    {
        $paradigmData = [
            'furnitures' => [],
            'progresses' => [],
        ];

        if ($group) {
            $paradigmData['furnitures'] = $this->groupService->getFurnituresFromId($group->id);
            $paradigmData['progresses'] = $this->groupService->getProgressFromId($group->id);
        }

        return $paradigmData;
    }

    /**
     * get all group children
     * @param Group $group
     * 
     * @return Illuminate\Support\Collection
     */
    public function getAllChildren(Group $group) : Collection
    {
        $children = new Collection();

        foreach ($group->children as $child) {
            $children->push($child);
            $children = $children->merge($this->getAllChildren($child));
        }

        return $children;
    }
}
