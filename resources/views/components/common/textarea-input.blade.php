<div class="form-group {{ $attributes['class'] }}">
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

  <textarea
    class="form-control {{ $inputClass }} {{ $isTinyMce ? 'js-tiny-textarea' : '' }} {{ $showError && $errors->has($name) ? 'is-invalid' : '' }}"
    {{ $name != null ? "name=$name" : '' }}
    {{ $id != null ? "id=$id" : '' }}
    placeholder="{{ $placeholder }}"
    cols="{{ $cols }}"
    rows="{{ $rows }}"
    {{ $readonly ? 'readonly' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {{ $autocomplete ? 'autocomplete' : '' }}
    {{ $autofocus ? 'autofocus' : '' }}
  >{!! $value !!}</textarea>
  {{-- >@if($isTinyMce){!! $value !!}@else{{ $value }}@endif</textarea> --}}

  @if($showError)
    {{ showError($errors, $name) }}
  @endif

  @if ($hint)
    <span class="font-italic">
      {{ $hint }}
    </span>
  @endif
</div>
