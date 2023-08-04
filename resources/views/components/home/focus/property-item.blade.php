@if (isset($group) && $group->id == 49 && $property->video_url)
  <div class="col-lg-4 col-sm-6">
    <div class="focus-list__item border c-mr-1 mb-3">
      <div class="px-2 pt-2">
        <div class="image-ratio-box relative">
          <a href="javascript:void(0);" class="express-new-item__image absolute-full js-focus__open-html5lightbox"
            data-id="{{ $property->id }}"
          >
            <img class="lazy object-cover" data-src="{{ $property->getImageUrl() }}" alt="">
            <div class="absolute-full flex-center">
              <span class="express-new-item__btn-play hover-shadow-darker flex-center square-size-50 rounded-circle bg-white fs-25">
                <i class="fas fa-play"></i>
              </span>
            </div>
          </a>
          <div class="text-white-shadow position-absolute bottom-0 end-0 px-2 py-1 fs-14">
            <i class="far fa-clock"></i> {{ getHumanTimeWithPeriod($property->created_at) }}
          </div>
        </div>

        <div class="item-body px-2 pb-2">
          <a href="javascript:void(0);"
            class="link focus-list__item-title text-ellipsis ellipsis-2 js-focus__open-html5lightbox"
            data-id="{{ $property->id }}"
          >
            {{ $property->news_title }}
          </a>
        </div>
      </div>
    </div>
  </div>
@else
  <div class="col-lg-4 col-sm-6">
    <x-home.focus.item :item="$property" class="mb-3">
      <x-slot name="detail">
        <div class="item-body px-2 pb-2">
          <a class="link focus-list__item-title text-ellipsis ellipsis-2"
            href="{{ data_get($property->group, 'group_url') ? route('home.focus.detail', [data_get($property->group, 'group_url'), $property->news_url]) : 'javascript:void(0);' }}">
            {{ $property->news_title }}
          </a>
        </div>
      </x-slot>
    </x-home.focus.item>
  </div>
@endif
