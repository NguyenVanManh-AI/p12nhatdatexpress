<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class Select2Input extends Component
{
    public $label;
    public $labelClass;
    public $required;
    public $inputClass;
    public $type;
    public $name;
    public $errorName;
    public $min;
    public $max;
    public $placeholder;
    public $readonly;
    public $disabled;
    public $autocomplete;
    public $autofocus;
    public $error;
    public $hint;
    public $items;
    public $itemText;
    public $itemValue;
    public $itemsSelectName;
    public $itemsCurrentValue;
    public $dataSelected;
    public $id;
    public $showError; // should default false
    public $multiple;
    public $maxLength;
    public $dropdownClass;
    public $select2Parent;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = null,
        $labelClass = null,
        $required = null,
        $inputClass = null,
        $type = 'text',
        $name = null,
        $min = null,
        $max = null,
        $placeholder = '',
        $readonly = null,
        $disabled = null,
        $autocomplete = null,
        $autofocus = null,
        $error = '',
        $hint = '',
        $items = [],
        $itemText = 'name',
        $itemValue = 'id',
        $itemsSelectName = null,
        $itemsCurrentValue = null,
        $dataSelected = null,
        $id = null,
        $showError = true,
        $multiple = false,
        $maxLength = null,
        $dropdownClass = '',
        $select2Parent = null
    ) {
        $this->label = $label;
        $this->labelClass = $labelClass;
        $this->required = $required;
        $this->inputClass = $inputClass;
        $this->type = $type;
        $this->name = $name;
        $this->errorName = preg_replace('/\[\]/', '', $this->name);
        $this->min = $min;
        $this->max = $max;
        $this->placeholder = $placeholder;
        $this->readonly = $readonly;
        $this->disabled = $disabled;
        $this->autocomplete = $autocomplete;
        $this->autofocus = $autofocus;
        $this->error = $error;
        $this->hint = $hint;
        $this->items = $items;
        $this->itemText = $itemText;
        $this->itemValue = $itemValue;
        $this->itemsSelectName = $itemsSelectName ?: $name;
        $this->itemsCurrentValue = $itemsCurrentValue;
        if ($multiple) {
            $this->itemsCurrentValue = is_array($itemsCurrentValue) ? $itemsCurrentValue : [];
        }
        $this->dataSelected = $dataSelected;
        $this->id = $id;
        $this->showError = $showError;
        $this->multiple = $multiple;
        $this->maxLength = $maxLength;
        $this->dropdownClass = $dropdownClass;
        $this->select2Parent = $select2Parent;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.select2-input');
    }
}
