<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class TextareaInput extends Component
{
    public $label;
    public $labelClass;
    public $required;
    public $name;
    public $value;
    public $cols;
    public $rows;
    public $placeholder;
    public $readonly;
    public $disabled;
    public $autocomplete;
    public $autofocus;
    public $error;
    public $hint;
    public $id;
    public $isTinyMce;
    public $inputClass;
    public $showError;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = null,
        $labelClass = null,
        $required = null,
        $name = null,
        $value = null,
        $cols = 30,
        $rows = 10,
        $placeholder = '',
        $readonly = null,
        $disabled = null,
        $autocomplete = null,
        $autofocus = null,
        $error = '',
        $hint = '',
        $id = null,
        $isTinyMce = false,
        $inputClass = null,
        $showError = true
    ) {
        $this->label = $label;
        $this->labelClass = $labelClass;
        $this->required = $required;
        $this->name = $name;
        $this->value = $value;
        $this->cols = $cols;
        $this->rows = $rows;
        $this->placeholder = $placeholder;
        $this->readonly = $readonly;
        $this->disabled = $disabled;
        $this->autocomplete = $autocomplete;
        $this->autofocus = $autofocus;
        $this->error = $error;
        $this->hint = $hint;
        $this->id = $id;
        $this->isTinyMce = $isTinyMce;
        $this->inputClass = $inputClass;
        $this->showError = $showError;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.textarea-input');
    }
}
