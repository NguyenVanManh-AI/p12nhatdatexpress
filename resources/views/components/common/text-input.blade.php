<div class="form-group {{ $attributes['class'] }}">
  @if(isset($slot_label) && $slot_label)
    {{ $slot_label }}
  @elseif($label)
    <label class="{{ $labelClass }}">
      {{ $label }}
      @if($required)
        <span class="text-danger">*</span>
      @endif
    </label>
  @endif

  <div class="c-input-field">
    @if(isset($prepend) && $prepend)
      <div class="c-input__prepend">
        {{ $prepend }}
      </div>
    @endif

    <div
      class="c-input__control form-control {{ $inputClass }} {{ $readonly ? 'c-input__readonly' : '' }} {{ $showError && $errors->has($errorName) ? 'is-invalid' : '' }}"
    >
      @if(isset($prependInner) && $prependInner)
        <div class="c-input__prepend-inner mr-2">
          {{ $prependInner }}
        </div>
      @endif
      <input
        type="{{ $type }}"
        {{ $name != null ? "name=$name" : '' }}
        {{ $id != null ? "id=$id" : '' }}
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $min != null ? "min=$min" : '' }}
        {{ $max != null ? "max=$max" : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $autocomplete ? 'autocomplete' : '' }}
        {{ $autofocus ? 'autofocus' : '' }}
      >
      @if(isset($appendInner) && $appendInner)
        <div class="c-input__append-inner ml-2">
          {{ $appendInner }}
        </div>
      @endif
    </div>

    @if(isset($append) && $append)
      <div class="c-input__append">
        {{ $append }}
      </div>
    @endif

    @if($hint)
      <span class="font-italic c-input__hint">
        {{ $hint }}
      </span>
    @endif

    @if($showError)
      {{ showError($errors, $errorName) }}
    @endif
  </div>
</div>