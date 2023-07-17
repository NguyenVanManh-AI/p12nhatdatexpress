@if($list->count() !=0)
<div class="mb-5">
    <div class="head title-classified-watch mb-3">
        <h3>Tin đăng đã xem</h3>
    </div>

    <div class="owl-carousel owl-hover-nav owl-drag classified-slide__carousel w-100">
        @foreach($list as $item)
            <x-classified.owl-carousel-item :item="$item"/>
        @endforeach
    </div>

    {{-- should change same above --}}
    {{-- <div class="list classified-curent news-viewed">
        @foreach($list as $item)
        <div class="item">
            <div class="thumbnail">
                <a href="{{route('home.classified.detail', [$item->group->getLastParentGroup(), $item->classified_url])}}">
                    <img src="{{ $item->getThumbnailUrl() }}">
                </a>
            </div>
            <div class="desc">
                <div class="title">
                    <a class="text-ellipsis ellipsis-3 text-break fs-16" style="min-height: 72px;" href="{{route('home.classified.detail', [$item->group->getLastParentGroup(), $item->classified_url])}}">{{$item->classified_name}}</a>
                </div>
                <div class="meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            {{ data_get($item->location, 'district.district_name' . ', ' . data_get($item->location, 'province.province_name')) }}
                        </span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tags"></i>
                        <span>{{ $item->getPriceWithUnit() }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-vector-square"></i>
                        <span>{{ $item->classified_area . ' ' . data_get($item->unit_area, 'unit_name') }}</span>
                    </div>
                </div>
                <x-common.phone-number :phone="$item->contact_phone" />
            </div>
        </div>
        @endforeach
    </div> --}}
</div>
@endif
<style>
    .slick-track{
        /*height: 400px;*/
    }
    .classified-curent .item{
        position: relative;
        /* height: 350px; */
        border: 1px solid rgba(222, 222, 222, 0.58);

    }
    .classified-curent .item .thumbnail{
        max-height: 50%;
        height: 50%;
        /*padding: 1px;*/
    }
    .classified-curent .item .desc{
        border: none;
    }
    .classified-curent .item .desc .title a{
        font-size: 14px;
    }
    .classified-curent .item .thumbnail img{
     height: 100%;
     object-fit: cover;
    }
</style>
