<div class="row popup-view-map__box">
  <div class="col-md-7-3">
    <div class="flex-column position-relative">
      <x-common.loading class="inner popup-view-map__load-utilities"/>

      {{-- <iframe class="mapparent"
        src="https://maps.google.com/maps?q={{ data_get($classified->location, 'map_latitude') }},{{ data_get($classified->location, 'map_longtitude') }}&hl=vi&z=14&output=embed"
        width="100%" height="465px" style="border:0;" allowfullscreen="" loading="lazy"
      >
      </iframe>

      <input name="map-api" value="{!! getGoogleApiKey() !!}" type="hidden">
      <input name="latitude" value="{{ data_get($classified->location, 'map_latitude') }}" type="hidden">
      <input name="longtitude" value="{{ data_get($classified->location, 'map_longtitude') }}" type="hidden">
      <input name="full_address" value="{{ $classified->getFullAddress() }}" type="hidden"> --}}

      <x-common.map
        id="view-map__map"
        lat-name="latitude"
        long-name="longtitude"
        lat-value="{{ data_get($classified->location, 'map_latitude') }}"
        long-value="{{ data_get($classified->location, 'map_longtitude') }}"
        hide-hint
      />
      <x-common.map-utilities class="view-map__popup" />
    </div>
  </div>
  <div class="col-md-3-7 h-500">
    @if ($classified->user)
      <x-home.user.agency-detail :user="$classified->user">
        <x-slot name="footerAction">
          <div class="pt-2 px-3 text-center">
            <a href="{{ $classified->group ? route('home.classified.detail', [$classified->group->getLastParentGroup(), $classified->classified_url]) : 'javascript:void(0);' }}"
              class="btn btn-success btn-sm fs-14"
            >
              Xem chi tiết tin đăng
            </a>
          </div>
        </x-slot>
      </x-home.user.agency-detail>
    @else
      <x-home.user.agency-not-login :item="$classified">
        <x-slot name="footerAction">
          <div class="pt-2 px-3 text-center">
            <a href="{{ $classified->group ? route('home.classified.detail', [$classified->group->getLastParentGroup(), $classified->classified_url]) : 'javascript:void(0);' }}"
              class="btn btn-success btn-sm fs-14"
            >
              Xem chi tiết tin đăng
            </a>
          </div>
        </x-slot>
      </x-home.user.agency-not-login>
    @endif
  </div>
</div>
