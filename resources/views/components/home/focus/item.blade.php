@if($item)
  <div class="focus-list__item border c-mr-1">
    <div class="px-2 pt-2">
      <div class="image-ratio-box relative">
        <a
          class="absolute-full"
          href="{{ route('home.focus.detail', [data_get($item->group, 'group_url'), $item->news_url]) }}"
        >
          <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}" alt="">
        </a>
        <div class="text-white-shadow position-absolute bottom-0 end-0 px-2 py-1 fs-14">
          <i class="far fa-clock"></i> {{ getHumanTimeWithPeriod($item->created_at) }}
        </div>
      </div>
    </div>

    <div class="item-body px-3 pt-3 pb-2">
      <div class="title mb-1">
        <h3 class="font-weight-bold mb-0">
          <a
            class="link focus-list__item-title text-ellipsis ellipsis-2"
            href="{{ route('home.focus.detail', [data_get($item->group, 'group_url'), $item->news_url]) }}"
          >
            {{ $item->news_title }}
          </a>
        </h3>
      </div>
      <div class="mb-1">
        <span class="focus-list__item-description text-ellipsis ellipsis-3 fs-normal">
          {{ $item->news_description }}
        </span>
      </div>
    </div>
  </div>
@endif
