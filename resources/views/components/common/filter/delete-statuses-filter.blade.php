<div>
  <x-common.select2-input
    label="{{ $label }}"
    name="{{ $name }}"
    :items="$deleteStatuses"
    item-text="label"
    item-value="value"
    placeholder="{{ $placeHolder }}"
    items-current-value="{{ $name != null ? request($name) : null }}"
  />
</div>
