<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class FileBoxInput extends Component
{
    public $base64File;
    public $imageValue;
    public $name;
    public $oldName;
    public $previewHeight;
    public $previewInline;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $base64File = false,
        $imageValue = null,
        $name,
        $oldName = null,
        $previewHeight = null,
        $previewInline = false
    ) {
        $this->base64File = $base64File;
        $this->imageValue = $imageValue;
        $this->name = $name;
        $this->oldName = $oldName ?: $name;
        $this->previewHeight = $previewHeight;
        if (!$previewHeight) {
            $this->previewHeight = $previewInline ? '90' : '200';
        }
        $this->previewInline = $previewInline ? true : false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.file-box-input');
    }
}
