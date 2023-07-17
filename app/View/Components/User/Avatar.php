<?php

namespace App\View\Components\User;

use Illuminate\View\Component;

class Avatar extends Component
{
    public $avatar;
    public $imageClass;
    public $link;
    public $isFancyBox;
    public $width; // image with as px
    public $height; // image height as px
    public $rounded; // image border radius as px
    public $frame;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $avatar = null,
        $imageClass = null,
        $link = null,
        $isFancyBox = true,
        $width = 36,
        $height = 36,
        $rounded = 18,
        $frame = null
    ) {
        $this->avatar = $avatar;
        $this->imageClass = $imageClass;
        $this->link = $link;
        $this->isFancyBox = $link ? false : $isFancyBox;
        $this->width = $width;
        $this->height = $height;
        $this->rounded = $rounded;
        $this->frame = $frame;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.avatar');
    }
}
