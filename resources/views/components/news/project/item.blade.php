<div class="col-md-4 col-sm-6 project-item project-item-box mb-3">
  <div class="wrapper h-100">
    <div class="thumbnail thumbnail-project">
      <a href="{{ route('home.project.project-detail', $item->project_url) }}">
        <img class="lazy" data-src="{{ $item->image_thumbnail }}">
      </a>
    </div>
    <div class="content">
      <a href="{{ route('home.project.project-detail', $item->project_url) }}"
        class="name text-ellipsis">{{ $item->project_name }}</a>
      <div class="price">
        <span>Giá</span>
        <span>:
          <span class="bold text-orange">
            {{ $item->getPriceWithUnit() }}
          </span>
        </span>
      </div>
      <div class="area">
        <span>Diện tích</span>
        <span>:
          {{ $item->getAreaLabel() }}
        </span>
      </div>
      <div class="location">
        <span>Vị trí</span>
        <span>: {{ data_get($item, 'location.district.district_name') . ', ' . data_get($item, 'location.province.province_name') }}</span>
      </div>
      <div class="status">
        <span>Tình trạng</span>
        <span>:
          <span class="green"> {{ data_get($item->progress, 'progress_name') }} </span>
        </span>

        <a href="#" class="update update-project" data-action="3" data-group-id="{{ $item->group_id }}"
          data-href="{{ route('home.project.update-project', $item->id) }}"
          data-selected="{{ $item->project_progress }}">(Cập nhật)</a>
      </div>
    </div>
    <div class="map">
      <a href="javascript:void(0);" class="js-view-map view-map" data-id="{{ $item->id }}">
        <img src="{{ asset('frontend/images/map-marker-1.png') }}">
        Xem bản đồ
      </a>
    </div>
  </div>
</div>
