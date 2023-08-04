<div class="form-group {{ $attributes['class'] }} {{ $hasCount ? 'js-has-count__area' : '' }}">
  @if (isset($slot_label) && $slot_label)
    {{ $slot_label }}
  @elseif($label)
    <div class="{{ $hasCount ? 'flex-between' : '' }}">
      <label class="{{ $labelClass }}">
        {{ $label }}
        @if($required)
          <span class="text-danger">*</span>
        @endif
      </label>
      @if($hasCount)
        <span>Số ký tự: <b class="js-has-count__length">0</b></span>
      @endif
    </div>
  @endif

  <textarea
    class="form-control {{ $isTinyMce ? 'js-admin-tiny-textarea' : '' }} {{ $showError && $errors->has($name) ? 'is-invalid' : '' }} {{ $hasCount ? 'js-has-count__input' : '' }}"
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
