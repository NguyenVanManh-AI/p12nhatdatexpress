<?php

namespace App\View\Components\Project;

use App\Models\Banner\Banner;
use App\Models\Banner\BannerGroup;
use Illuminate\View\Component;
use App\Models\Group;

class BannerMobile extends Component
{
    public $banner_center_mobile;
    public $group_id;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {   

        $this->getBanner();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {   
        
        return view('components.project.banner-mobile', [
            'banner_center_mobile'=>$this->banner_center_mobile,
        ]);
    }

    /**
     * Get Banner
     * @return void
     */
    public function getBanner(){
        // Check group
        foreach (Group::all() as $item){
            if (str_contains(url()->current(), $item->group_url)){
                $this->group_id = $item->id;
                break;
            }
        }
        // Check banner_group
        $banner_group_center_id = BannerGroup::showed()
            ->where('banner_position','C')
            ->where('banner_group', $this->group_id > 1 ? 'C' : 'H')
            ->first('id')->id;
     

        // Check banner
        $this->banner_center_mobile  = Banner::showed()
            ->where('banner_group_id' , '=' ,10)
            ->where('group_id', $this->group_id)
            ->where('date_from' , '<=', time())
            ->where('date_to','>=' , time())
            ->orderBy('id', 'desc')
            ->first();
        
        // If banner not schedule
        if (!$this->banner_center_mobile){
            $this->banner_center_mobile  = Banner::showed()
                ->where('banner_group_id' , '=' ,10)
                ->where('group_id', $this->group_id)
                ->where('date_from', null)
                ->orderBy('id', 'desc')
                ->first();
        }
      
    }
}
