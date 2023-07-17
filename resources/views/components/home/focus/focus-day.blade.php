<div class="widget widget-event c-mb-10">
    <div class="widget-title">
        <h3 class="text-center">Tiêu điểm trong ngày</h3>
    </div>
    <div class="widget-content">
        @if($top_1)
        <div class="list-focus-day">
            <div class="item-focus-day top-1">
                <div class="thumbnail">
                    <a href="{{route('home.focus.detail', [$top_1->group_url, $top_1->news_url])}}">
                        <img src="{{asset($top_1->image_url)}}" alt="">
                    </a>
                </div>
                <div class="content">
                    <h3 class="title">
                        <a href="{{route('home.focus.detail', [$top_1->group_url, $top_1->news_url])}}">{{$top_1->news_title}}</a>
                    </h3>
                </div>
            </div>
            @foreach($list as $item)
            <div class="item-focus-day">
                <div class="thumbnail">
                    <a href="{{route('home.focus.detail', [$item->group_url, $item->news_url])}}">
                        <img src="{{asset($item->image_url)}}" alt="">
                    </a>
                </div>
                <div class="content">
                    <h3 class="title">
                        <a href="{{route('home.focus.detail', [$item->group_url, $item->news_url])}}">{{$item->news_title}}</a>
                    </h3>
                    <span class="post-time"><i class="far fa-clock"></i> {{getHumanTimeWithPeriod($item->created_at)}}</span>
                </div>
            </div>
            @endforeach
        </div>

        @else
            <p class="text-center m-0">Hôm nay chưa có tiêu điểm</p>
        @endif
    </div>
</div>
