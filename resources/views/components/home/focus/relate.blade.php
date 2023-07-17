@if($list->count() > 1)
<div class="post-related">
    <h3>Bài liên quan</h3>
    <ul class="list-related">
        @foreach($list as $item)
        <li class="item">
            <a href="{{route('home.focus.detail', [$item->group_url, $item->news_url])}}"> {{$item->news_title}}
                <span class="post-time" style="color: {{get_diff_minute($item->created_at) >= 15 ? '#777' : '#6eb86c'}}"> - {{getHumanTimeWithPeriod($item->created_at)}}</span>
            </a>
        </li>
        @endforeach
    </ul>
</div>
@else
    <div class="pb-4"></div>
@endif
