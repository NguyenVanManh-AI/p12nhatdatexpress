<?php

namespace App\View\Components\Home;

use App\Models\Survey as ModelsSurvey;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Survey extends Component
{
    public $surveys;
    public $surveyLists;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->surveys = ModelsSurvey::select(DB::raw('avg(rating) as avg_rating, count(*) as length, type'))
            ->groupBy('surveys.type')
            ->get();

        $this->surveyLists = config('home.surveys.list', []);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.survey');
    }
}
