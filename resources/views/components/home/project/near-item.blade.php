<div class="near-item mb-3">
  <a href="{{ route('home.project.project-detail', $item->project_url) }}"
    class="name text-ellipsis ellipsis-2 text-break fs-14">
    {{ $item->project_name }}
  </a>
  <div class="content d-flex">
    <div class="thumbnail mr-2">
      <a href="{{ route('home.project.project-detail', $item->project_url) }}">
        <img class="object-cover lazy" data-src="{{ asset($item->image_thumbnail) }}" alt="">
      </a>
    </div>
    <div class="info fs-12">
      <div class="d-flex flex-wrap">
        <span class="info-item price mr-2">Giá:
					<span class="text-dark-cyan">{{ \App\Helpers\Helper::format_money($item->project_price) ?? \App\Helpers\Helper::format_money($item->project_rent_price) ?? "Đang cập nhật" . data_get($item->unitSell, 'unit_name') }}</span>
        </span>
        <span class="info-item area">Diện tích:
					<span class="text-dark-cyan">{{ $item->project_area_from . ' ' . data_get($item->unitArea, 'unit_name') }}</span>
        </span>
      </div>
      <span class="info-item location flex-start">Vị trí:
				<span class="text-dark-cyan text-ellipsis flex-1 ml-1 c-w-1">{{ data_get($item, 'location.district.district_name') . ', ' . data_get($item, 'location.province.province_name') }}</span>
      </span>
      @if($item->distance)
        <div class="info-item map mt-1">
					<i class="fas fa-map-marker-alt"></i>{{ $item->distance }} km
				</div>
      @endif
    </div>
  </div>
</div>
