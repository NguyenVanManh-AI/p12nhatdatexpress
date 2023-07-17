<?php

namespace App\Services;

use App\Models\Furniture;
use App\Models\Group;
use App\Models\Progress;

class GroupService
{
    /**
     * get group from group url
     *
     * @param string $groupUrl
     * @param string|null $childGroupUrl = null
     *
     * @return $group
     */
    public function getGroupFromUrl($groupUrl, $childGroupUrl = null)
    {
        $group = Group::select('group.*')
            ->leftJoin('group as group_parent', 'group.parent_id', '=', 'group_parent.id')
            ->leftJoin('group as group_parent_parent', 'group_parent.parent_id', '=', 'group_parent_parent.id')
            ->where('group.group_url', $childGroupUrl ?? $groupUrl)
            ->when($childGroupUrl, function ($query) use ($groupUrl) {
                $query->where(function ($query) use ($groupUrl) {
                    $query->where('group_parent_parent.group_url', $groupUrl)
                        ->orWhere('group_parent.group_url', $groupUrl);
                });
            })
            ->first();

        return $group;
    }

    /**
     * get group children select from parent group id
     * @param $parentGroupId|null
     *
     * @return $children
     */
    public function getChildrenSelectFromGroupId($parentGroupId)
    {
        if (!$parentGroupId) return [];
        $children = Group::select('id', 'group_name')
            ->with('allChildren')
            ->where('parent_id', $parentGroupId)
            ->get();

        return $children;
    }

    /**
     * get ancestor group from group
     * @param $group|null
     * @return Group|null $group
     */
    public function getAncestorGroupFromGroup($group)
    {
        if (!$group) return null;

        $ancestor = $group;
        $parent = $group->parent;

        while (!is_null($parent)) {
            $ancestor = $parent;
            $parent = $parent->parent;
        }

        return $ancestor;
    }

    /**
     * get group furnitures from group id
     * @param $groupId
     *
     * @return Collection $furnitures
     */
    public function getFurnituresFromId($groupId)
    {
        return Furniture::select('id', 'furniture_name')
            ->where('group_id', $groupId)
            ->get();
    }

    /**
     * get group progress from group id
     * @param $groupId
     *
     * @return Collection $progress
     */
    public function getProgressFromId($groupId)
    {
        return Progress::select('id', 'progress_name')
            ->where('group_id', $groupId)
            ->get();
    }

    /**
     * Sync paradigm dependencies
     * @param Group $group
     * @param array $dependencyIds
     * 
     * @return void
     */
    public function syncParadigmDependencies(Group $group, array $dependencyIds)
    {
        $group->dependencies()->sync($dependencyIds);
    }

    // old should check
    public function getChildren($parentId, $trashed = false)
    {
        return Group::where('group.parent_id', $parentId)
            ->when($trashed, function ($query) {
                return $query->onlyIsDeleted();
            })
            ->join('admin', 'group.created_by', '=', 'admin.id')
            ->select('group.*', 'admin.rol_id')
            ->get();
    }

    function prepareData($collection, $trashed = false)
    {
        // parent item
        $parent_item = $collection;
        $count = 1;
        foreach ($collection as $item) {

            $child = Group::where('group.parent_id', $item->id)
                ->when($trashed, function ($query) {
                    return $query->onlyIsDeleted();
                })
                ->join('admin', 'group.created_by', '=', 'admin.id')
                ->select('group.*', 'admin.rol_id')
                ->get();
            if ($child->count() > 0) {
                foreach ($child as $chid) {
                    $chid->child = true;
                }
                $left = $parent_item->take($count);
                $right = $parent_item->take(- ($parent_item->count() - ($count)));
                $parent_item = $left->merge($child->merge($right));
                $count += $child->count() + 1;
            } else {
                $count++;
            }
        }
        return $parent_item;
    }
    // end old should check
}
