<div class="property-main-content">
    @if($item->getImagesUrl())
    <div class="owl-carousel owl-custom-theme owl-dot-orange owl-hover-nav owl-nav-rounded owl-drag detail-banner__carousel w-100">
        @foreach($item->getImagesUrl() as $image)
            <div class="image-ratio-box-half relative">
                <a href="{{ $image }}" class="absolute-full" data-fancybox="gallery">
                    <img class="object-cover" src="{{ $image }}" alt="">
                </a>
            </div>
        @endforeach
    </div>
    @endif

    <div class="px-2 pt-3">
        <h2 class="single-title">{{$item->classified_name}}</h2>
        <div class="single-meta mb-4">
            <div class="left flex-1">
                <div class="price bold text-danger prepend-image-icon-price">
                    {{ $item->getPriceWithUnit() }}
                </div>
                <div class="area bold text-danger prepend-image-icon-area">
                    {{number_format($item->classified_area,0,'','.')}} {{$item->unit_area->unit_name}}
                </div>
                <div class="location bold text-danger prepend-image-icon-address">
                    {{ data_get($item->location, 'district.district_name') . ', ' . data_get($item->location, 'province.province_name') }}
                </div>
            </div>
            <div class="right">
                {{-- @if($item->user_id == null)
                <span class="share-button">
                    <i class="fas fa-share-alt"></i>Chia sẻ

                    @if(!$disableAction)
                    <div class="share-sub">
                        <a class="item" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{request()->url()}}">
                            <i class="fab fa-facebook-square"></i>
                            Facebook
                        </a>
                    </div>
                    @endif
                </span>
                @endif --}}
                <span class="report-button report-content {{ $disableAction ? 'disable-action' : '' }}" data-id="{{$item->id}}">
                    <i class="fas fa-exclamation"></i>Báo cáo
                </span>
            </div>
        </div>
        <div class="single-contact-buttons">
            <a href="#" class="green">
                <i class="fas fa-phone-alt"></i>
                Liên hệ
            </a>
            <a href="#" class="blue">
                <i class="fas fa-user"></i>
                Đăng ký thông tin
            </a>
        </div>

        <div class="property-detail mb-4">
            <div class="mb-4">
                <div class="text-break">
                    <span class="text-pre-wrap">{{ $item->classified_description }}</span>
                </div>
                <div class="text-right">
                    <span class="text-gray mr-1">
                        <i class="fas fa-chart-bar"></i>
                    </span>
                    Lượt xem:
                    <span class="text-light-cyan">{{ $item->num_view }}</span>
                </div>
            </div>

            <div class="mb-4">
                <x-news.classified.item-table :item="$item" />
            </div>

            <div class="single-tabs mb-0">
                <div class="tab-switchers">
                    <div class="switcher active" data-tabid="tab-1">Bản đồ</div>
                    @if($item->video)
                        <div class="switcher" data-tabid="tab-2">Video</div>
                    @endif
                </div>
                <div class="tabs p-0">
                    <div class="tab active position-relative js-map-load-utilities" id="tab-1">
                        {{-- <iframe
                            class="mapparent border-none"
                            src="https://www.google.com/maps/embed/v1/place?key={!! getGoogleApiKey() !!}&q={{ data_get($item->location, 'map_latitude') }},{{ data_get($item->location, 'map_longtitude') }}"
                            width="100%"
                            height="400px"
                            loading="lazy"
                        >
                        </iframe>
                        <input name="map-api" value="{!! $google_api_key !!}" type="hidden">
                        <input name="latitude" value="{{ data_get($item->location, 'map_latitude') }}" type="hidden">
                        <input name="longtitude" value="{{ data_get($item->location, 'map_longtitude') }}" type="hidden">
                        <input name="full_address" value="{{ $item->getFullAddress() }}" type="hidden">
                        <x-common.map-utilities /> --}}

                        <x-common.loading class="inner map__load-utilities"/>

                        <x-common.map
                            id="classified-detail-page__map"
                            lat-name="latitude"
                            long-name="longtitude"
                            lat-value="{{ data_get($item->location, 'map_latitude') }}"
                            long-value="{{ data_get($item->location, 'map_longtitude') }}"
                            hide-hint
                        />

                        <x-common.map-utilities />
                    </div>
                    @if($item->video)
                        <div class="tab" id="tab-2">
                            <iframe width="100%" height="436px" src="{{ $item->video }}"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if(count($key_search))
        <div class="single-tags">
            <div class="title">
                <i class="fas fa-tag"></i>Tìm theo từ khóa:
            </div>
            <div class="tags">
                @foreach($key_search as $keyword)
                @if($keyword['type'] == 0)
                    <a class="text-lowercase link-light-cyan" href="{{$url}}?province_id={{ data_get($item->location, 'district.province_id') }}&district_id={{ data_get($item->location, 'district.id') }}">
                        {{$keyword['title']}}
                        @if( $loop->index + 1 < count($key_search) )
                        , &nbsp;
                        @endif
                    </a>
                @else
                    <a class="text-lowercase link-light-cyan" href="{{$url}}?province_id={{ data_get($item->location, 'district.province_id') }}">
                        {{$keyword['title']}}
                        @if( $loop->index + 1 < count($key_search) )
                        , &nbsp;
                        @endif
                    </a>
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
