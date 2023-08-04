<div class="widget widget-height widget-news c-mb-10">
  <div class="widget-title">
    <h3 class="text-center">Đọc nhiều</h3>
  </div>
  <div class="widget-content">
    <div class="list-news">
      @foreach ($list as $item)
        <div class="news-item">
          <div class="thumbnail">
            <a href="{{ $item->group ? route('home.focus.detail', [$item->group->group_url, $item->news_url]) : 'javascript:void(0);' }}">
              <a href="{{ $item->group ? route('home.focus.detail', [$item->group->group_url, $item->news_url]) : 'javascript:void(0);' }}">
              <img class="lazy" data-src="{{ $item->getImageUrl() }}" alt="">
            </a>
          </div>
          <div class="content d-flex justify-content-between flex-column">
            <h3 class="title">
              <a
                href="{{ $item->group ? route('home.focus.detail', [$item->group->group_url, $item->news_url]) : 'javascript:void(0);' }}"
                class="text-ellipsis-2 lh-12 fs-14"
              >
                {{ $item->news_title }}
              </a>
            </h3>
            <div class="text-right text-gray fs-13">
              <i class="far fa-clock"></i>
              {{ getHumanTimeWithPeriod($item->created_at) }}
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
