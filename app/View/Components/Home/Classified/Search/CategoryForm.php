<?php

namespace App\View\Components\Home\Classified\Search;

use App\Models\Classified\ClassifiedParam;
use App\Models\Direction;
use App\Models\Group;
use App\Models\Project;
use App\Services\GroupService;
use Illuminate\View\Component;

class CategoryForm extends Component
{
    public $provinces;
    public $searchPriceSell;
    public $searchAreaSell;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->provinces = get_cache_province();
        $this->searchPriceSell = config('constants.classified.search.price.sell', []);
        $this->searchAreaSell = config('constants.classified.search.area.sell', []);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $project = Project::showed()->get();
        $direction = Direction::get();

        $classifiedParams = ClassifiedParam::whereIn('param_type', ['A', 'B', 'P'])->get();
        $numBed = $classifiedParams->where('param_type', 'B');
        $numPeople = $classifiedParams->where('param_type', 'P');
        $advance = $classifiedParams->where('param_type', 'A');

        // active category button
        $path = request()->path();
        $pathArr = explode('/', $path);
        $showNeedBuy = isset($pathArr[0]) && $pathArr[0] == 'can-mua-can-thue' ? true : false;
        $activeCategory = $pathArr[0];

        $validCategories = [
            'nha-dat-ban', 'nha-dat-cho-thue', 'du-an',
            'can-mua-can-thue'
        ];

        $activeCategory = in_array($activeCategory, $validCategories) ? $activeCategory : null;
    
        if ($showNeedBuy) {
            $activeCategory = 'can-mua';
        
            if (isset($pathArr[1]) && $pathArr[1]) {
                $activeCategory = $pathArr[1];
        
                if (!in_array($pathArr[1], ['can-mua', 'can-thue'])) {
                    $activeCategoryModel = Group::firstWhere('group_url', $activeCategory);
                    $activeCategory = data_get($activeCategoryModel, 'parent.group_url', 'can-mua');
                }
            }
        }

        return view('components.home.classified.search.category-form', [
            'project' => $project, 
            'direction' => $direction,
            'num_bed' => $numBed,
            'num_people' => $numPeople,
            'advance' => $advance,
            'activeCategory' => $activeCategory,
            'path' => $path
        ]);
    }
}
