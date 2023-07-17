<?php

namespace App\View\Components\Home;

use App\Services\Classifieds\ClassifiedService;
use Illuminate\View\Component;

class EstateNews extends Component
{
    public $classified;
    public $properties;
    public $watched_classified;
    public $onLastPage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(ClassifiedService $classifiedService)
    {
        $estateNews = $classifiedService->getEstateNews();
        $this->classified = $estateNews;
        $this->onLastPage = $estateNews->onLastPage();
        $this->properties= getCacheProperties();
        $this->watched_classified = getWatchedClassifieds();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.estate-news');
    }
}
