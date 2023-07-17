<?php

namespace App\View\Components\Home\Classified;

use App\Models\Classified\Classified;
use App\Models\Project;
use App\Services\Classifieds\ClassifiedService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Relate extends Component
{
    public $classifieds;
    public $individualUrl;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($classifiedId = null, $projectId = null )
    {
        $data = [
            'classified_id' => $classifiedId,
            'project_id' => $projectId,
            'load_individual' => true
        ];
        $this->classifieds = (new ClassifiedService())->getRelates($data);
        $this->individualUrl = "/classifieds/relates?classified_id=$classifiedId&project_id=$projectId";
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.classified.relate');
    }
}
