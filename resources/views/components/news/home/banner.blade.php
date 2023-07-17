<div id="banner-area" class="centered d-none d-md-block">
  @if ($banner_left)
    <a
      class="banner-box bg-white"
      href="{{ $banner_left->link }}"
      target="{{ $banner_left->target_type == 1 ? '_blank' : '' }}"
      style="background-image: url({{ asset($banner_left->image_url) }})"
    >
    </a>
  @endif
  @if ($banner_right)
    <a
      class="banner-box bg-white right"
      href="{{ $banner_right->link }}"
      target="{{ $banner_right->target_type == 1 ? '_blank' : '' }}"
      style="background-image: url({{ asset($banner_right->image_url) }})"
    >
    </a>
  @endif
</div>
