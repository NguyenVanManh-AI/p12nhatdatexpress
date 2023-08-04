{{-- old code --}}
<div class="section fengshui mb-4 focus-fengshui-box">
  <div class="section-title">
    <h2 class="title">{{$group->group_name}}</h2>
    <a href="{{route('home.focus.list-children', $group->group_url)}}" class="custom-view-more">Xem thêm</a>
  </div>
  @if($list->count() > 0)
    <div class="row">
      <div class="col-md-7 mb-3 mb-md-0">
        <div class="fengshui-box__main">
          <div class="fengshui-box__main-image image-ratio-box-75 relative">
            <a
              class="absolute-full"
              href="{{ route('home.focus.detail', [data_get($top_1->group, 'group_url'), $top_1->news_url]) }}"
            >
              <img class="lazy object-cover" data-src="{{ $top_1->getImageUrl() }}" alt="">
            </a>
            <div class="position-absolute w-100 bottom-0 start-0 text-white">
              <div class="text-white-shadow text-right px-2 py-1 fs-14">
                <i class="far fa-clock"></i> {{ getHumanTimeWithPeriod($top_1->created_at) }}
              </div>
              <div class="position-relative bg-overlay p-2">
                <a
                  class="fs-normal text-ellipsis ellipsis-2 text-white bold hover-color-danger"
                  href="{{ route('home.focus.detail', [data_get($top_1->group, 'group_url'), $top_1->news_url]) }}"
                >
                  {{ $top_1->news_title }}
                </a>
                <span class="text-ellipsis ellipsis-2 fs-14">
                  {{ $top_1->news_description }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="fengshui-box__list scrollable">
          @foreach($list as $item)
            <div class="fengshui-box__list-item d-flex {{ $loop->index + 1 < count($list) ? 'c-mb-15' : '' }}">
              <div class="image-ratio-box-75 w-size-108 relative">
                <a
                  class="absolute-full"
                  href="{{ route('home.focus.detail', [data_get($item->group, 'group_url'), $item->news_url]) }}"
                >
                  <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}" alt="">
                </a>
              </div>
              <div class="fengshui-box__list-item-detail d-flex flex-column justify-content-between flex-1 px-2 pt-2 pb-0">
                <h3 class="title">
                  <a class="text-ellipsis ellipsis-2 fs-14 link-dark lh-1"
                    href="{{ route('home.focus.detail', [data_get($item->group, 'group_url'), $item->news_url]) }}">
                    {{ $item->news_title }}
                  </a>
                </h3>
                <div class="text-gray pr-2 pt-1 fs-14">
                  <i class="far fa-clock"></i>
                  {{ getHumanTimeWithPeriod($item->created_at) }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @else
    <div class="text-center p-5">
      <p>Chưa có dữ liệu</p>
    </div>
  @endif
</div>
