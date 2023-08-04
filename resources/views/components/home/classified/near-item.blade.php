<div class="near-item mb-3">
  <a href="{{ $item->getShowUrl() ?: 'javascript:void(0);' }}"
    class="name text-ellipsis ellipsis-2 text-break fs-14 lh-1">
    {{ $item->classified_name }}
  </a>
  <div class="content d-flex">
    <div class="thumbnail mr-2">
      <a href="{{ $item->getShowUrl() ?: 'javascript:void(0);' }}">
        <img class="object-cover lazy" data-src="{{ $item->getThumbnailUrl() }}" alt="">
      </a>
    </div>
    <div class="info fs-12">
      <div class="d-flex flex-wrap">
        <span class="info-item price mr-2">Giá:
					<span class="text-dark-cyan">{{ $item->getPriceWithUnit() }}</span>
        </span>
        <span class="info-item area">Diện tích:
					<span class="text-dark-cyan">{{ $item->getAreaLabel() }}</span>
        </span>
      </div>
      <span class="info-item location flex-start">Vị trí:
				<span class="text-dark-cyan text-ellipsis flex-1 ml-1 c-w-1">
          {{ $item->getFullAddress(['province', 'district']) }}
        </span>
      </span>
      @if($item->distance)
        <div class="info-item map mt-1">
					<i class="fas fa-map-marker-alt"></i>{{ $item->distance }} km
				</div>
      @endif
    </div>
  </div>
</div>
