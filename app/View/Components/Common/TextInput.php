<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class TextInput extends Component
{
    public $label;
    public $labelClass;
    public $required;
    public $inputClass;
    public $type;
    public $name;
    public $value;
    public $min;
    public $max;
    public $placeholder;
    public $readonly;
    public $disabled;
    public $autocomplete;
    public $autofocus;
    public $error;
    public $hint;
    public $id;
    public $showError; // should default false
    public $errorName;

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
        $value = null,
        $min = null,
        $max = null,
        $placeholder = '',
        $readonly = null,
        $disabled = null,
        $autocomplete = null,
        $autofocus = null,
        $error = '',
        $hint = '',
        $id = null,
        $showError = true,
        $errorName = null
    ) {
        $this->label = $label;
        $this->labelClass = $labelClass;
        $this->required = $required;
        $this->inputClass = $inputClass;
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->min = $min;
        $this->max = $max;
        $this->placeholder = $placeholder;
        $this->readonly = $readonly;
        $this->disabled = $disabled;
        $this->autocomplete = $autocomplete;
        $this->autofocus = $autofocus;
        $this->error = $error;
        $this->hint = $hint;
        $this->id = $id;
        $this->showError = $showError;
        $this->errorName = $errorName ?: $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.text-input');
    }
}
