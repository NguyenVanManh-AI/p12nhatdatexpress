@if($item)
<div class="knowledge-item focus-knowledge__box mb-3 {{ $item->isAds() ? 'is-highlight' : '' }}">
    <div class="thumbnail">
        <div class="image-ratio-box relative">
            @if($item->isAds())
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

    <div class="d-flex flex-column justify-content-between pl-3">
        <div>
            <h3 class="focus-knowledge__title">
                <a
                    href="{{route('home.focus.detail', [$group->group_url, $item->news_url])}}"
                    class="text-ellipsis ellipsis-2 fs-18 lh-12 {{ $item->isAds() ? 'link-red-flat' : 'link' }}" 
                >{{ $item->news_title }}</a>
            </h3>
            <span class="text-muted">
                <i class="far fa-clock"></i>
                {{ getHumanTimeWithPeriod($item->created_at) }}
            </span>
        </div>
        <p class="text-ellipsis ellipsis-3 lh-1 mb-0 d-flex align-items-end">
            {{ $item->news_description }}
        </p>
    </div>
</div>
@endif
