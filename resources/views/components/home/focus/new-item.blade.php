<div class="news-item">
    <div class="thumbnail">
        <a href="{{route('home.focus.detail', [$new->group_url, $new->news_url])}}">
            <img class="lazy" data-src="{{ asset($new->image_url) }}" alt="" style="height: 170px; object-fit: cover">
        </a>
    </div>

    <div class="content">
        <h3 class="title">
            <a href="{{route('home.focus.detail', [$new->group_url, $new->news_url])}}"> {{$new->news_title}} </a>
        </h3>

        <span class="post-time"><i class="far fa-clock"></i> {{getHumanTimeWithPeriod($new->created_at)}}</span>
        <div class="desc">
            {{$new->news_description}}
        </div>
    </div>
</div>
