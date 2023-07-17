<?php

namespace App\View\Components\Home;

use App\Models\HomeConfig;
use Illuminate\View\Component;

class VirtualTitle extends Component
{
    public $home_config;
    public $numVirtual;
    public $textHeaderSplit;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->home_config = HomeConfig::latest('id')
            ->first(['header_text_block', 'desktop_header_image', 'mobile_header_image']);

        $this->numVirtual = $this->getVirtual(data_get($this->home_config, 'header_text_block'));
        $this->textHeaderSplit = $this->getArrayStringSplit(data_get($this->home_config, 'header_text_block'));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.virtual-title');
    }

    /**
     * Get number virtual
     *
     * @param string $header_text_block
     * @return int
     */
    public function getVirtual(string $header_text_block): string
    {
        preg_match('/\%\d{1,}.\d{1,}\W/', $header_text_block, $num_virtual);

        return implode('', str_replace(['%', '.', '+', ','], '', $num_virtual));
    }

    /**
     * Get Array split from string
     *
     * @param string $header_text_block
     * @return array
     */
    public function getArrayStringSplit(string $header_text_block): array
    {
        return preg_split('/\%\d{1,}.\d{1,}\W/', $header_text_block);
    }
}
