<div class="banner-top-mobile-section js-home-search-box">
  <div class="banner-mobile home-banner">
    <div class="banner"
      style="background-image: url({{ asset('system/img/home-config/' . $home_config->mobile_header_image) }})">
      <h2>nhà đất</h2>
      <h1>Express</h1>

      <x-home.virtual-title class="legend" />
    </div>

    <div class="search choose-price-box mb-3">
      <div class="head">Tìm kiếm Express</div>
      <div class="search-list price-list">
        @foreach ($classified_sell_search_price as $homePrice)
          {{-- show 4 items of list for mobile --}}
          @if ($loop->index < 4)
            <span class="item js-choose-price-btn" data-value="{{ data_get($homePrice, 'value') }}">
              {{ data_get($homePrice, 'mobile_label', data_get($homePrice, 'sub_label', data_get($homePrice, 'label'))) }}
            </span>
          @endif
        @endforeach
      </div>
    </div>

    <div class="js-need-toggle-active mb-4">
      <button
        class="btn btn-success btn-lg btn-block bold text-uppercase fs-14 mb-3 {{ isAcceptLocation() ? 'js-disable-search-near' : 'js-search-near' }}"
        data-url="{{ isAcceptLocation() ? route('param.remove-location') : '' }}"
      >
        <i class="far fa-compass fs-18 mr-1"></i>
        {{ isAcceptLocation() ? 'Xóa vị trí' : 'Tìm gần tôi' }}
      </button>

      <form action="" class="js-home-search-form home-search-mobile-form">
        <x-common.text-input name="keyword" placeholder="Nhập từ khóa cần tìm" />

        <x-common.select2-input name="category" :items="$categories" item-text="group_name" item-value="group_url"
          placeholder="Chọn chuyên mục" :show-error="false" />
        <x-common.select2-input name="paradigm" placeholder="Chọn mô hình" :show-error="false" />

        <div class="home-advanced-box p-1 bg-white mt-1 js-toggle-area-slide" style="display:none;">
          <div class="custom-select-form group-load-address">
            <x-news.common.address />

            <x-common.select2-input name="price" placeholder="Chọn mức giá" :show-error="false" />

            <x-common.select2-input name="direction" placeholder="Chọn hướng nhà" :items="$directions"
              item-text="direction_name" :show-error="false" />

            <x-common.select2-input name="area" placeholder="Chọn diện tích" :show-error="false" />
          </div>
        </div>

        <div class="position-relative js-home-search__footer">
          <div class="position-absolute start-0 bottom-0 pb-2 pl-2 js-toggle-area">
            <a href="javascript:void(0);" class="text-light-cyan js-toggle-active">
              <span>Nâng cao</span>
              <i class="fas fa-caret-up active-rotate text-gray"></i>
            </a>
          </div>

          <div class="text-center">
            <button type="button" class="btn btn-light-cyan js-search-btn">
              <i class="fas fa-search"></i>
              <span>Tìm kiếm</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- <div class="banner-mobile home-search">
    <div class="search-tool-form project-type active">
      <form action="" class="home-search-mobile-form js-home-search-form" id="search-tool-form">
        <div class="group-btn">
          <button class="btn-find js-search-btn search-mobile-button" type="button"><i
              class="fas fa-search"></i><span>Tìm kiếm</span></button>
        </div>
      </form>
    </div>
  </div> --}}
</div>
