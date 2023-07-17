<div class="widget widget-height widget-news c-mb-10">
    <div class="widget-title">
        <h3 class="text-center">Đọc nhiều</h3>
    </div>
    <div class="widget-content">
        <div class="list-news">
            @foreach($list as $item)
                <div class="news-item">
                <div class="thumbnail">
                    <a href="{{route('home.focus.detail', [$item->group_url, $item->news_url])}}">
                        <img class="lazy" data-src="{{asset($item->image_url)}}" alt="">
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
    </div>
</div>
