@extends('Home.Layouts.Master')

@section('Title', $province->getSeoTitle())
@section('Keywords', $province->getSeoKey())
@section('Description', $province->getSeoDescription())
@section('Image', $province->getSeoBanner())

@section('Content')
  <div id="page-property" class="list-search-category-box js-parents-loadmore pt-2">
    <div class="row">
      <div class="col-md-12 banner-mobile">
        <div class="banner">
          <h2>{{ 'Nhà đất ' . $province->getLabel() }}</h2>

          <x-home.virtual-title class="legend"/>
        </div>
        {{-- should change --}}
        <div class="search">
          <div class="head" class="">Tìm kiếm Express</div>
          <div class="search-list">
            <div class="item" style="background-color: #128CBB">&lt;1 tỷ</div>
            <div class="item" style="background-color: #0076A3">1 - 3 tỷ</div>
            <div class="item" style="background-color: #004A80">3 - 5 tỷ</div>
            <div class="item" style="background-color: #003471">5 - 7 tỷ</div>
            <div class="item-full">
              <img src="{{ asset('frontend/images/compass.png') }}" alt=""> tìm bất động sản gần đây
            </div>
          </div>
        </div>
        {{-- end should change --}}
      </div>
      <div class="col-md-12 search-banner list-classified-search-box d-inline-block">
        <div class="row">
          <div class="search-tool search-tool-width col-md-3-7 mb-3">
            <x-home.classified.search.category-form />
          </div>
          <div class="banner-slide-width col-md-7-3 mb-3 md-hide">
            <div class="search-image"
              style="background-image: url({{ $province->getImageUrl() }})"></div>
          </div>
        </div>
      </div>
    </div>

    <div id="category-search-results-section">
      <x-news.classified.search-results
        :province="$province"
        :classifieds="$classifieds"
      />
    </div>
  </div>
@endsection

@section('Script')
  <script type="text/javascript">
    $("body").on("click", ".popup .close-button", function(event) {
      event.preventDefault();
      let popup = $(this).parents(".popup");
      popup.hide();
      $("#layout").hide();
      $("#location_classified").empty()
    });

    $("body").on("click", "#layout", function(event) {
      event.preventDefault();
      $("#map-fixed").hide();
      $("#location_classified").empty()
    });

    $("body").on("click", ".location_classified", function(event) {
      event.preventDefault();
      viewMap($(this).data('id'));
    });
  </script>
@endsection
