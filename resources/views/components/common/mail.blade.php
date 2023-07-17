<span class="show-mail__part">
  @if(data_get($mailAsArray, '0') && data_get($mailAsArray, '1'))
    <span
      class="show-mail__domain cursor-pointer"
      data-pre-part="{{ data_get($mailAsArray, '0') }}"
      data-last-part="{{ data_get($mailAsArray, '1') }}"
    >
  @endif
</span>