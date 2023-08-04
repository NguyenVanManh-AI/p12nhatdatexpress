<div class="widget widget-event c-mb-10">
  <div class="widget-title">
    <h3 class="text-center">Tiêu điểm trong ngày</h3>
  </div>
  <div class="widget-content">
    @if ($top_1)
      <div class="list-focus-day">
        <div class="item-focus-day top-1">
          <div class="image-ratio-box relative">
            <a class="absolute-full"
              href="{{ $top_1->group ? route('home.focus.detail', [data_get($top_1->group, 'group_url'), $top_1->news_url]) : 'javascript:void(0);' }}">
              <img class="lazy object-cover" data-src="{{ $top_1->getImageUrl() }}" alt="">
            </a>
            <div class="text-white-shadow position-absolute bottom-0 end-0 px-2 py-1 fs-14">
              <i class="far fa-clock"></i> {{ getHumanTimeWithPeriod($top_1->created_at) }}
            </div>
          </div>

          <div class="content">
            <h3 class="title text-ellipsis-2 lh-1">
              <a
                href="{{ $top_1->group ? route('home.focus.detail', [data_get($top_1->group, 'group_url'), $top_1->news_url]) : 'javascript:void(0);' }}"
              >{{ $top_1->news_title }}</a>
            </h3>
          </div>
        </div>
        @foreach ($list as $item)
          <div class="item-focus-day">
            <div class="thumbnail">
              <a
                href="{{ $item->group ? route('home.focus.detail', [data_get($item->group, 'group_url'), $item->news_url]) : 'javascript:void(0);' }}">
                <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}" alt="">
              </a>
            </div>
            <div class="content">
              <h3 class="title text-ellipsis-2 lh-1">
                <a
                  href="{{ $item->group ? route('home.focus.detail', [data_get($item->group, 'group_url'), $item->news_url]) : 'javascript:void(0);' }}"
                >{{ $item->news_title }}</a>
              </h3>
              <span class="post-time"><i class="far fa-clock"></i>
                {{ getHumanTimeWithPeriod($item->created_at) }}</span>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-center m-0">Hôm nay chưa có tiêu điểm</p>
    @endif
  </div>
</div>
