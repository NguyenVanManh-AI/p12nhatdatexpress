<?php

namespace App\View\Components\Home\Classified;

use App\Models\AdminConfig;
use App\Models\Classified\Classified;
use App\Models\Direction;
use App\Models\Project;
use App\Models\Province;
use App\Services\GroupService;
use App\Services\ParamService;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class GuestAddPopup extends Component
{
    private GroupService $groupService;
    private ParamService $paramService;
    public $group;
    public $paradigm;
    public $progress;
    public $furniture;
    public $classifiedParams;
    public $direction;
    public $unit_price;
    public $province;
    public $project;
    public $guide;
    public $classified;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->groupService = new GroupService;
        $this->paramService = new ParamService;

        $group = $this->groupService->getGroupFromUrl('nha-dat-ban');
        $groupParentId = $group->id;
        $this->group = $group;
        $this->paradigm = $this->groupService->getChildrenSelectFromGroupId($groupParentId);
        $this->progress = new Collection();
        $this->furniture = new Collection();
        $this->classifiedParams = $this->paramService->getClassifiedParamsByTypes(['A', 'B', 'L', 'P', 'T']);
        $this->direction = Direction::select('id', 'direction_name', 'is_show')
            ->showed()
            ->get();
        $this->unit_price = $this->paramService->getGroupUnitPrice($groupParentId);
        $this->province = Province::select('id', 'province_name', 'is_show')
            ->showed()    
            ->get();
        $this->project = Project::select('id', 'project_name')
            ->showed()
            ->get();
        $this->guide = AdminConfig::select('config_code', 'config_value')
            ->whereIn('config_code', ['N006', 'N007'])
            ->get();
        $this->classified = new Classified([
            'price_unit_id' => data_get($this->unit_price, '0.id'),
        ]);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.classified.guest-add-popup');
    }
}
