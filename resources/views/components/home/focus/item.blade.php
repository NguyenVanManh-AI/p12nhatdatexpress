@if($item)
  <div class="focus-list__item border c-mr-1 {{ $attributes['class'] }}">
    <div class="px-2 pt-2">
      <div class="image-ratio-box relative">
        <a
          class="absolute-full"
          href="{{ $item->group ? route('home.focus.detail', [data_get($item->group, 'group_url'), $item->news_url]) : 'javascript:void(0);' }}"
        >
          <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}" alt="">
        </a>
        <div class="text-white-shadow position-absolute bottom-0 end-0 px-2 py-1 fs-14">
          <i class="far fa-clock"></i> {{ getHumanTimeWithPeriod($item->created_at) }}
        </div>
      </div>
    </div>

    @if(isset($detail) && $detail)
      {{ $detail }}
    @else
      <div class="item-body px-3 pt-3 pb-2">
        <div class="title mb-1">
          <h3 class="font-weight-bold mb-0">
            <a
              class="link focus-list__item-title text-ellipsis ellipsis-2 lh-12"
              href="{{ data_get($item->group, 'group_url') ? route('home.focus.detail', [data_get($item->group, 'group_url'), $item->news_url]) : 'javascript:void(0);' }}"
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
    @endif
  </div>
@endif
