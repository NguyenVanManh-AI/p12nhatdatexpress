@if($item)
<div class="knowledge-item mb-3 {{ $item->is_highlight ? 'is-highlight' : '' }}">
    {{-- <div class="thumbnail relative">
        @if($item->is_ads)
            <span class="ads">Quảng cáo</span>
        @endif
        <a href="{{ route('home.focus.detail', [$group->group_url, $item->news_url]) }}">
            <img src="{{asset($item->image_url)}}" alt="">
        </a>
    </div> --}}

    <div class="thumbnail">
        <div class="image-ratio-box relative">
            @if($item->is_ads)
                <span class="ads">Quảng cáo</span>
            @endif
            <a
                class="absolute-full"
                href="{{ route('home.focus.detail', [$group->group_url, $item->news_url]) }}"
            >
            <img class="object-cover" src="{{ $item->getImageUrl() }}" alt="">
            </a>
        </div>
    </div>

    <div class="content">
        <h3 class="title">
            <a
                href="{{route('home.focus.detail', [$group->group_url, $item->news_url])}}"
                class="text-ellipsis ellipsis-2 {{ $item->is_ads ? 'link-red-flat' : 'link' }}" 
            >{{ $item->news_title }}</a>
        </h3>
        <span class="post-time"><i class="far fa-clock"></i> {{getHumanTimeWithPeriod($item->created_at)}}</span>
        <div class="desc text-ellipsis ellipsis-3">{{$item->news_description}}</div>
    </div>
</div>
@endif
