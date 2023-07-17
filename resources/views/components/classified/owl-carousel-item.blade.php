<div class="item classified-slider__item border c-mr-1">
  <div class="px-2 pt-2">
    <div class="image-ratio-box relative">
      <a
        class="absolute-full"
        href="{{ $item->group ? route('home.classified.detail', [$item->group->getLastParentGroup(), $item->classified_url]) : 'javascript:void(0);' }}"
      >
        <img class="object-cover" src="{{ $item->getThumbnailUrl() }}" alt="">
      </a>
      {{-- @if(isset($showViewTag) && $showViewTag)
        <div class="position-absolute top-0 start-0 c-w-50">
          <img src="{{ asset('frontend/images/view-classified.png') }}">
        </div>
      @endif --}}
    </div>
  </div>

  <div class="item-body px-3 pt-3 pb-2">
    <div class="title mb-1">
      <h3 class="mb-0">
        <a
          class="link item-title text-ellipsis ellipsis-3 text-break fs-16"
          href="{{ $item->group ? route('home.classified.detail',[$item->group->getLastParentGroup(), $item->classified_url]) : 'javascript:void(0);' }}"
        >
          {{ $item->classified_name }}
        </a>
      </h3>
    </div>
    <div class="mb-1 text-dark bold">
      <span class="flex-start prepend-image-icon-address">
        {{ data_get($item->location, 'district.district_name') . ', ' . data_get($item->location, 'province.province_name') }}
      </span>
    </div>
    <div class="mb-1 text-dark bold">
      <span class="flex-start prepend-image-icon-price">{{ $item->getPriceWithUnit() }}</span>
    </div>
    <div class="flex-between text-dark bold" style="height: 30px">
      <span class="flex-start prepend-image-icon-area">
        {{ $item->classified_area . ' ' . data_get($item->unit_area, 'unit_name') }}
      </span>
      <x-common.phone-number :phone="$item->contact_phone" />
    </div>
  </div>
</div>