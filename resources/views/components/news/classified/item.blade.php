<div class="col-12 classified-item-box flex-column px-3 pt-2 pb-2 bg-white rounded shadow mb-3 cursor-pointer">
  <div class="item-box">
    <h3 class="item-title text-uppercase fs-18">
      <a href="{{ route('home.classified.detail', [$item->group->getLastParentGroup(), $item->classified_url]) }}"
          class="d-flex {{ $item->isVip() || $item->isHighlight() ? 'link-red-flat' : 'link' }}">
        @if($item->isHighlight())
          <img class="hot-image ml-1" src="{{ asset('frontend/images/unnamed.gif') }}">
        @endif
        <span class="text-ellipsis d-inline-block">
          {{ $item->classified_name }}
        </span>
      </a>
    </h3>

    <div class="content-box">
      <div class="d-flex">
        <div class="thumbnail-box relative">
          <a
            href="{{ route('home.classified.detail', [$item->group->getLastParentGroup(), $item->classified_url]) }}"
          >
            <div class="thumbnail-image">
              <img class="lazy object-cover" data-src="{{ $item->getThumbnailUrl() }}">
            </div>

            @if(in_array($item->id, $watched_classified))
              <span class="is-watched flex-center"></span>
            @endif
            <div class="star-rating pl-1 light-yellow">
              @for($i =0; $i< 5; $i++)
                @if($item->rating >$i)
                  <i class="fas fa-star"></i>
                @else
                  <i class="text-white fas fa-star"></i>
                @endif
              @endfor
            </div>
          </a>
        </div>

        <div class="info-detail-box d-flex flex-column justify-content-between pl-3 pr-2 flex-1">
          <div>
            <div class="flex-between meta-title pb-2 flex-wrap">
              <div class="meta-box d-flex flex-wrap flex-1">
                <div class="meta-item mr-3 text-gray prepend-image-icon-price">
                  {{-- <i class="fas fa-tags mr-1"></i> --}}
                  <span class="text-orange bold">{{ $item->getPriceWithUnit() }}</span>
                </div>
                <div class="meta-item mr-3 text-gray prepend-image-icon-area">
                  {{-- <i class="fas fa-vector-square mr-1"></i> --}}
                  <span class="text-blue">{{ $item->classified_area . " " . data_get($item->unit_area, 'unit_name') }}</span>
                </div>
                <div class="meta-item mr-3 text-gray prepend-image-icon-address">
                  {{-- <i class="fas fa-map-marker-alt mr-1"></i> --}}
                  <span class="text-blue">
                    {{ data_get($item, 'location.district.district_name') . ', ' . data_get($item, 'location.province.province_name') }}
                  </span>
                </div>
              </div>
              <div>
                <a href="#" class="js-view-map text-danger" data-id="{{ $item->id }}">
                  <img src="{{ asset('/frontend/images/map-marker-1.png') }}">
                  <span>Xem bản đồ</span>
                </a>
              </div>
            </div>

            <div class="description-box text-ellipsis ellipsis-2 text-break fs-16">
              {!! strlen(strip_tags($item->classified_description)) > 300 ? substr(strip_tags($item->classified_description), 0, 299) : strip_tags($item->classified_description) !!}
            </div>
          </div>

          <div class="item-foot-box item-foot-desktop cursor-pointer js-show-detail flex-between">
            <x-common.phone-number :phone="$item->contact_phone" />

            <div class="time user-select-none">
              <i class="far fa-clock text-blue mr-1"></i>
              <span>{{ \App\Helpers\Helper::get_time_to($item->created_at) }}</span>
              <i class="fas fa-angle-double-down show-detail-icon ml-1"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="item-foot-box item-foot-mobile cursor-pointer js-show-detail flex-between">
        <div class="item-foot-box flex-between w-100 py-2">
          <x-common.phone-number :phone="$item->contact_phone" />

          <div class="time user-select-none">
            <i class="far fa-clock text-blue mr-1"></i>
            <span>{{ \App\Helpers\Helper::get_time_to($item->created_at) }}</span>
            <i class="fas fa-angle-double-down show-detail-icon ml-1"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="detail-box mt-3">
      <x-news.classified.item-table :item="$item" />
    </div>
  </div>
</div>
