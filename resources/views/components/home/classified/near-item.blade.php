<div class="near-item mb-3">
  <a href="{{ route('home.classified.detail', [$item->group_parent_parent_url ?? $item->group_parent_url, $item->classified_url]) }}"
    class="name text-ellipsis ellipsis-2 text-break fs-14">
    {{ $item->classified_name }}
  </a>
  {{-- <a href="{{route('home.classified.detail',[$item->group->getLastParentGroup(),$item->classified_url])}}" class="name">{{$item->classified_name}}</a> --}}
  <div class="content d-flex">
    <div class="thumbnail mr-2">
      {{-- <a href="{{route('home.classified.detail',[$item->group_parent_parent_url??$item->group_parent_url,$item->classified_url])}}"> --}}
      <a href="{{ route('home.classified.detail', [$item->group_parent_parent_url ?? $item->group_parent_url, $item->classified_url]) }}">
        <img class="object-cover lazy" data-src="{{ $item->getThumbnailUrl() }}" alt="">
      </a>
    </div>
    <div class="info fs-12">
      <div class="d-flex flex-wrap">
        <span class="info-item price mr-2">Giá:
					<span class="text-dark-cyan">{{ $item->getPriceWithUnit() }}</span>
        </span>
        <span class="info-item area">Diện tích:
					<span class="text-dark-cyan">{{ $item->classified_area . ' ' . $item->unit_area->unit_name }}</span>
        </span>
      </div>
      <span class="info-item location flex-start">Vị trí:
				<span class="text-dark-cyan text-ellipsis flex-1 ml-1 c-w-1">{{ $item->location->district->district_name . ', ' }}{{ $item->location->province->province_name }}</span>
      </span>
      @if($item->distance)
        <div class="info-item map mt-1">
					<i class="fas fa-map-marker-alt"></i>{{ $item->distance }} km
				</div>
      @endif
    </div>
  </div>
</div>
