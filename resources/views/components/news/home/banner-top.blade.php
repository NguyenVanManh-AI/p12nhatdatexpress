<x-home.banner-top-mobile :homeConfig="$home_config" :directions="$directions" />
<div class="home-banner-top-section js-home-search-box d-md-block d-none mb-3">
  <div
    class="banner-top-inner position-relative image-ratio-box"
    style="background-image: url({{ asset('system/img/home-config/' . $home_config->desktop_header_image) }})"
  >
    <x-common.loading class="inner"/>
    <div class="flex-center flex-column h-100 absolute-full py-3 px-2">
      <h2 class="banner-title text-center text-uppercase">
        Nhà đất express
      </h2>

      <x-home.virtual-title class="banner-sub-title text-center fs-20"/>

      <form action="" class="home-search-form-box js-home-search-form w-100">
        <div class="home-search-inner w-md-75 w-90 mx-auto">
          <div class="category-box d-flex">
            <div class="radio-with-button">
              <input type="radio" name="category" value="nha-dat-ban">
              <button type="button" class="btn button-with-radio bold rounded-top-left py-1">
                Cần bán
              </button>
            </div>
            <div class="radio-with-button">
              <input type="radio" name="category" value="nha-dat-cho-thue">
              <button type="button" class="btn button-with-radio bold py-1">
                Cho thuê
              </button>
            </div>
            <div class="radio-with-button">
              <input type="radio" name="category" value="du-an">
              <button type="button" class="btn button-with-radio bold py-1">
                Dự án
              </button>
            </div>

            <x-common.accept-location
              enable-text="Tìm gần đây"
              disable-text="Quay lại"
              icon-class="d-none"
              class="bold rounded-top-right py-1"
            />
          </div>
          <div class="search-filter-box mb-3">
            <div class="d-flex">
              <div class="input-box flex-1">
                <input type="text" name="keyword" class="form-control rounded-0">
              </div>
              <button type="button" class="open-advanced-button py-0 px-3 btn btn-white rounded-0 font-weight-bold">
                <i class="fas fa-cog"></i> <span class="advance-button__text d-none">Nâng cao</span>  
              </button>
              <button type="button" class="search-button js-search-btn btn btn-blue rounded-0 p-0 font-weight-bold">
                Tìm
                <i class="fas fa-search ml-2"></i>
              </button>
            </div>
            <div class="home-advanced-box p-1 bg-white mt-1">
              <div class="custom-select-form group-load-address">
                <x-common.select2-input
                  class="mb-0"
                  name="paradigm"
                  placeholder="-- Chọn mô hình --"
                  :show-error="false"
                />

                <x-common.select2-input
                  class="mb-0"
                  input-class="province-load-district"
                  name="province_id"
                  placeholder="-- Chọn tỉnh/Thành Phố  --"
                  :items="$provinces"
                  item-text="province_name"
                  :show-error="false"
                />

                <x-common.select2-input
                  class="mb-0"
                  input-class="district-province"
                  name="district_id"
                  placeholder="-- Chọn quận/Huyện --"
                  :show-error="false"
                />

                <x-common.select2-input
                  class="mb-0"
                  name="price"
                  placeholder="-- Chọn mức giá --"
                  :show-error="false"
                />

                <x-common.select2-input
                  class="mb-0"
                  name="direction"
                  placeholder="-- Chọn hướng nhà --"
                  :items="$directions"
                  item-text="direction_name"
                  :show-error="false"
                />

                <x-common.select2-input
                  class="mb-0"
                  name="area"
                  placeholder="-- Chọn diện tích --"
                  :show-error="false"
                />
              </div>
            </div>
          </div>

          <div class="choose-price-box text-center">
            {{-- <h4 class="price-title text-white mb-2 text-shadow">Chọn mức giá</h4> --}}
            <div class="choose-price-box__list">
              <ul class="price-list flex-center price-sell flex-wrap">
                @foreach($classified_sell_search_price as $homePrice)
                  {{-- show 5 items of price list --}}
                  @if($loop->index < 5)
                  <li class="item js-choose-price-btn c-mx-10 mb-2 fs-normal" data-value="{{ data_get($homePrice, 'value') }}">
                    <span class="item-content">{{ data_get($homePrice, 'sub_label', data_get($homePrice, 'label')) }}</span>
                  </li>
                  @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
