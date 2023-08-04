<div class="highlight-box__main js-focus__lightbox-parent {{ isset($withList) && $withList ? 'with-list' : '' }}">
  <div class="highlight-box__main-image image-ratio-box relative">
    <div class="absolute-full">
      <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}" alt="">
      @if ($item->video_url)
        <a href="javascript:void(0);" class="js-focus__open-html5lightbox"
          data-id="{{ $item->id }}"
        >
          <span
            class="express-new-item__btn-play hover-shadow-darker flex-center square-size-50 rounded-circle bg-white fs-25">
            <i class="fas fa-play"></i>
          </span>
        </a>
      @endif
    </div>
    <div class="position-absolute w-100 bottom-0 start-0 text-white">
      <div class="text-white-shadow text-right px-2 py-1 fs-14">
        <i class="far fa-clock"></i> {{ getHumanTimeWithPeriod($item->created_at) }}
      </div>
      <div class="position-relative bg-overlay p-2">
        @if($item->video_url)
          <a href="javascript:void(0);"
            class="fs-normal text-ellipsis w-100 text-white bold hover-color-danger js-focus__open-html5lightbox"
            data-id="{{ $item->id }}"
          >
            {{ $item->news_title }}
          </a>
        @else
          <span class="fs-normal text-ellipsis w-100 text-white bold hover-color-danger">
            {{ $item->news_title }}
          </span>
        @endif

        <span class="text-ellipsis ellipsis-2 fs-14">
          {{ $item->news_description }}
        </span>
      </div>
    </div>
  </div>
</div>