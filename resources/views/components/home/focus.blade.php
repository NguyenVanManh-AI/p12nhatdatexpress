@if($focus->count() != 0)
<section class="focus-section mb-4">
    <div class="text-center">
        <h3 class="title-sec">Tiêu điểm</h3>
        <p class="text-info font-italic fs-18">Thông tin thị trường bất động sản, cập nhật chính xác liên tục 24/7</p>
    </div>
    <section class="owl-carousel owl-hover-nav owl-drag classified-slide__carousel w-100">
        @foreach ($focus as $item )
        <div class="focus-section__item border c-mr-1">
            <div class="px-2 pt-2">
                <div class="image-ratio-box relative">
                    <a
                        class="absolute-full"
                        href="{{ route('home.focus.detail', [$item->group_url, $item->news_url]) }}"
                    >
                        <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}">
                    </a>
                </div>
            </div>

            <div class="p-3">
                <div class="focus-section__item-title">
                    <h4 class="mb-0">
                        <a href="{{route('home.focus.detail', [$item->group_url, $item->news_url])}}" class="link-dark text-ellipsis ellipsis-2 fs-18">{{$item->news_title}}</a>
                    </h4>
                </div>
                <div class="flex-start mb-2">
                    <p class="fs-13 mb-0">{{\App\Helpers\Helper::get_time_to($item->created_at)}}</p>
                </div>
                <div class="focus-section__item-description">
                    <p class="mb-0 text-ellipsis ellipsis-3">{{$item->news_description}}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
