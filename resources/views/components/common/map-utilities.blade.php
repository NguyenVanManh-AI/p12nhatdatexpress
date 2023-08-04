<div class="map-utilities__list {{ $attributes['class'] ?: 'px-2 pb-2' }}">
  <button
    class="map-utilities__item mr-2 mt-2 position-relative btn btn-sm btn-outline-gray-300 pl-5"
    data-value="Siêu+thị"
    data-types="supermarket,shopping_mall"
    data-icon="{{ asset('images/icons/map/supermarket-marker.png') }}"
  >
    <span class="map-utilities__item-icon text-success border-right border-gray-300 position-absolute start-0 top-0 h-100 flex-center">
      <i class="fas fa-shopping-cart"></i>
    </span>

    <strong class="map-utilities__item-text">Siêu thị</strong>
  </button>

  <button 
    class="map-utilities__item mr-2 mt-2 position-relative btn btn-sm btn-outline-gray-300 pl-5"
    data-value="Bến+xe"
    data-types="transit_station"
    {{-- data-types="subway_station,bus_station" --}}
    data-icon="{{ asset('images/icons/map/train_station-marker.png') }}"
  >
    <span class="map-utilities__item-icon text-primary border-right border-gray-300 position-absolute start-0 top-0 h-100 flex-center">
      <i class="fas fa-bus"></i>
    </span>

    <strong class="map-utilities__item-text">Bến xe</strong>
  </button>

  <button 
    class="map-utilities__item mr-2 mt-2 position-relative btn btn-sm btn-outline-gray-300 pl-5"
    data-value="Nhà+hàng"
    data-types="restaurant"
    data-icon="{{ asset('images/icons/map/restaurant-marker.png') }}"
  >
    <span class="map-utilities__item-icon text-warning border-right border-gray-300 position-absolute start-0 top-0 h-100 flex-center">
      <i class="fas fa-utensils"></i>
    </span>

    <strong class="map-utilities__item-text">Nhà hàng</strong>
  </button>

  <button 
    class="map-utilities__item mr-2 mt-2 position-relative btn btn-sm btn-outline-gray-300 pl-5"
    data-value="Trường+học"
    data-types="school,primary_school,secondary_school"
    data-icon="{{ asset('images/icons/map/school-marker.png') }}"
  >
    <span class="map-utilities__item-icon text-danger border-right border-gray-300 position-absolute start-0 top-0 h-100 flex-center">
      <i class="fas fa-graduation-cap"></i>
    </span>

    <strong class="map-utilities__item-text">Trường học</strong>
  </button>

  <button 
    class="map-utilities__item mr-2 mt-2 position-relative btn btn-sm btn-outline-gray-300 pl-5"
    data-value="Công+viên"
    data-types="park"
    data-icon="{{ asset('images/icons/map/park-marker.png') }}"
  >
    <span class="map-utilities__item-icon text-success border-right border-gray-300 position-absolute start-0 top-0 h-100 flex-center">
      <i class="fas fa-tree"></i>
    </span>

    <strong class="map-utilities__item-text">Công viên</strong>
  </button>
</div>
