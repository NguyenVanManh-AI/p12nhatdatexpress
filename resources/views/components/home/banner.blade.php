<div id="sidebar">
  <div class="banner-qc">
    <div class="container">
      @if(data_get($banner_left, 'image_urls'))
        <div class="left">
          <a href="{{ data_get($banner_left, 'link') }}" rel="noopener noreferrer nofollow" target="{{ data_get($banner_left, 'target_type') == 1 ? '_blank' : ''}}">
            <img class="lazy" data-src="{{ asset(data_get($banner_left, 'image_url')) }}">
          </a>
        </div>
      @endif
      @if(data_get($banner_right, 'image_url'))
        <div class="right">
          <a href="{{ data_get($banner_right, 'link') }}" rel="noopener noreferrer nofollow" target="{{ data_get($banner_right, 'target_type') == 1 ? '_blank' : ''}}">
            <img class="lazy" data-src="{{ asset(data_get($banner_right, 'image_url')) }}">
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
