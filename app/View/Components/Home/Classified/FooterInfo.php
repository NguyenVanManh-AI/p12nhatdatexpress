<?php

namespace App\View\Components\Home\Classified;

use App\Models\AdminConfig;
use Illuminate\View\Component;

class FooterInfo extends Component
{
    // private $group;
    // private $province;
    public $introContent;

    // public $info_group;
    // public $info_location;

    /**
     * Create a new component instance.
     * @param $group = null for /{group}/{child_group} e.g: /nha-dat-ban/can-ho-chung-cu
     * @param $province = null for /vi-tri/nha-dat-{province_url} e.g: /vi-tri/nha-dat-ha-noi
     *
     * @return void
     */
    public function __construct($group = null, $province = null)
    {
        $introCode = 'C002';
        $positionUrl = $positionLabel = $positionHtml = '';

        if ($province) {
            // location introduce
            $introCode = 'C003';

            $positionUrl = route('home.location.province-classified', $province->province_url);
            $positionLabel = $province->getLabel();
        } elseif ($group) {
            $positionUrl = route('home.classified.list', [$group->getLastParentGroup(), $group->parent_id ? $group->group_url : null]);
            $positionLabel = $group->group_name;
        }

        $positionHtml = '<a href="' . $positionUrl . '" class="text-danger">' . $positionLabel . '</a>';

        // introductory content
        $introConfig = AdminConfig::select('config_code', 'config_value')
            ->firstWhere('config_code', $introCode);
        $content = data_get($introConfig, 'config_value');
        $this->introContent = str_replace(['%vi_tri%','%chuyen_muc%'], $positionHtml , $content);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.classified.footer-info');
    }
}