<?php

namespace App\View\Components\Common\Filter;

use Illuminate\View\Component;

class DeleteStatusesFilter extends Component
{
    public $deleteStatuses;
    public $label;
    public $name;
    public $placeHolder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = 'Lọc theo',
        $name = 'trashed',
        $placeHolder = 'Tất cả'
    ) {
        $this->deleteStatuses = [
            [
                'value' => 'active',
                'label' => 'Chưa xóa',
            ],
            [
                'value' => 'with',
                'label' => 'Cùng với xóa',
            ],
            [
                'value' => 'only',
                'label' => 'Chỉ xóa',
            ],
        ];

        $this->label = $label;
        $this->name = $name;
        $this->placeHolder = $placeHolder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.filter.delete-statuses-filter');
    }
}
