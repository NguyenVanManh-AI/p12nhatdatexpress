<div class="form-group {{ $dropdownClass }} {{ $attributes['class'] }}">
  @if (isset($slot_label) && $slot_label)
    {{ $slot_label }}
  @elseif($label)
    <label class="{{ $labelClass }}">
      {{ $label }}
      @if ($required)
        <span class="text-danger">*</span>
      @endif
    </label>
  @endif

  <div class="flex-1">
    <select
      class="custom-select select2 {{ $inputClass }} {{ $showError && $errors->has($errorName) ? 'is-invalid' : '' }}"
      type="{{ $type }}"
      {{ $name != null ? "name=$name" : '' }}
      {{ $id != null ? "id=$id" : '' }}
      data-placeholder="{{ $placeholder }}"
      data-selected="{{ $dataSelected }}"
      data-dropdown-css-class="{{ $dropdownClass }}"
      data-select2-max-length="{{ $maxLength }}"
      data-select2-parent="{{ $select2Parent }}"
      {{ $multiple ? 'multiple' : '' }}
      {{ $readonly ? 'readonly' : '' }}
      {{ $disabled ? 'disabled' : '' }}
      {{ $autofocus ? 'autofocus' : '' }}
    >
      @if($items)
        @if($multiple)
          {{ showSelectMultipleOptions($items, $itemValue, $itemText, $itemsCurrentValue) }}
        @else
          {{ show_select_option($items, $itemValue, $itemText, $itemsSelectName, $itemsCurrentValue) }}
        @endif
      @endif
    </select>

    @if($showError)
      {{ showError($errors, $errorName) }}
    @endif
    @if ($hint)
      <span class="font-italic">
        {{ $hint }}
      </span>
    @endif
  </div>
</div>
