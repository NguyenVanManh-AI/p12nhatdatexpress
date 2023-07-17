@push('style')
    <style>
        @media screen and (min-width:720px) {
            .highlightTop1 {
                height: 500px;
            }
        }
        .hot-area img{
            filter: brightness(70%);
        }
    </style>
@endpush
<section class="hot-area c-p-20">
    <div class="text-center">
        <h3 class="title-sec">Khu vực bất động sản nổi bật</h3>
    </div>
    <div class="list-hot-area row">
        <div class="left-image col-md-6">
            @foreach($top_4 as $item)
                <div class="item relative">
                    {{-- <a class="image" href="{{route('home.classified.list',['group_url'=>'nha-dat-ban','province_id'=>$item->id])}}"> --}}
                    <a
                        class="image"
                        href="{{ route('home.location.province-classified', ['province_url' => $item->province_url]) }}"
                    >
                        <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}">
                        <div class="info">
                            <div class="post-count"><h4 class="fs-20">{{\App\Helpers\Helper::format_money(($item->classified_num ?? 1) * $post_fake)}} tin đăng</h4></div>
                            <div class="address"><span class="text-danger c-mr-10 text-uppercase"><i class="fas fa-map-marker-alt"></i></span><span>{{$item->province_name}}</span></div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="right-image col-md-6">
            <div class="owl-carousel owl-hover-nav owl-drag hot-area__carousel w-100">
            <div class="item highlightTop1" >
                {{-- <a class="image" href="{{route('home.classified.list',['group_url'=>'nha-dat-ban','province_id'=>$top_1->id])}}"> --}}
                <a
                    class="image"
                    href="{{ route('home.location.province-classified', ['province_url' => $top_1->province_url]) }}"
                >
                    <img class="lazy object-cover" data-src="{{ $top_1->getImageUrl() }}">
                    <div class="info">
                        <div class="post-count"><h4 class="fs-20">{{\App\Helpers\Helper::format_money(($top_1->classified_num ?? 1) * $post_fake)}} tin đăng</h4></div>
                        <div class="address"><span class="text-danger c-mr-10 text-uppercase"><i class="fas fa-map-marker-alt"></i></span><span>{{$top_1->province_name}}</span></div>
                    </div>
                </a>
            </div>
           @foreach($top_10 as $item)
                <div class="item highlightTop1" >
                    {{-- <a class="image" href="{{route('home.classified.location',[$item->province_url])}}"> --}}
                    <a
                        class="image"
                        href="{{ route('home.location.province-classified', ['province_url' => $item->province_url]) }}"
                    >
                        <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}">
                        <div class="info">
                            <div class="post-count"><h4 class="fs-20">{{\App\Helpers\Helper::format_money(($item->classified_num ?? 1) * $post_fake)}} tin đăng</h4></div>
                            <div class="address"><span class="text-danger c-mr-10 text-uppercase"><i class="fas fa-map-marker-alt"></i></span><span>{{$item->province_name}}</span></div>
                        </div>
                    </a>
                </div>
           @endforeach
            </div>
        </div>
    </div>
</section>
