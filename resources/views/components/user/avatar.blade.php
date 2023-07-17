<div class="d-inline-block position-relative {{ $attributes['class'] }}" style="width:{{ $width }}px;min-width:{{ $width }}px;height:{{ $height }}px;min-height:{{ $height }}px;">
  @if($isFancyBox)
    <a class="h-100 {{ $isFancyBox ? 'js-fancy-box' : '' }}" href="{{ $avatar }}">
      <img
        class="object-cover elevation-1 {{ $imageClass }}"
        style="border-radius:{{ $rounded }}px"
        src="{{ $avatar }}"
        alt=""
      >
    </a>
  @elseif($link)
    <a class="h-100" href="{{ $link }}">
      <img
        class="object-cover elevation-1 {{ $imageClass }}"
        style="border-radius:{{ $rounded }}px"
        src="{{ $avatar }}"
        alt=""
      >
    </a>
  @else
    <div class="h-100">
      @if($frame)
        <div class="avatar-with-frame__box absolute-full">
          <div class="avatar-with-frame__avatar absolute-full" style="background-image: url({{ $avatar }})">
          </div>
          <img class="avatar-with-frame__frame object-contain relative" src="{{ $frame }}" alt="">
        </div>
      @else
        <img
          class="object-cover elevation-1 {{ $imageClass }}"
          style="border-radius:{{ $rounded }}px"
          src="{{ $avatar }}"
          alt=""
        >
      @endif
    </div>
  @endif
</div>
