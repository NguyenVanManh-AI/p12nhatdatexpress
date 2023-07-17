<div>
  <div class="image-ratio-box-half relative">
    <div class="absolute-full">
        <div
          {{ $id != null ? "id=$id" : '' }}
          class="w-100 h-100"
          data-marker-icon="{{ $markerIcon }}"
        ></div>

        <input
          type="hidden"
          {{ $latName != null ? "name=$latName" : '' }}
          value="{{ $latValue }}"
        >

        <input
          type="hidden"
          {{ $longName != null ? "name=$longName" : '' }}
          value="{{ $longValue }}"
        >
    </div>
  </div>

  @if(!$hideHint)
    <span class="font-italic">
      {{ $hint ?: 'Kéo, thả ghim để thay đổi vị trí trên bản đồ' }}
    </span>
  @endif
</div>
