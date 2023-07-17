<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class MapEmbed extends Component
{
    public $map_latitude;
    public $map_longtitude;
    public $height;
    public $width;
    public $zoom;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($mapLatitude, $mapLongtitude, $width = '100%', $height = '550px', $zoom = 14)
    {
        $this->width = $width;
        $this->height = $height;
        $this->map_latitude = $mapLatitude;
        $this->map_longtitude = $mapLongtitude;
        $this->zoom = $zoom;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.map-embed');
    }
}
