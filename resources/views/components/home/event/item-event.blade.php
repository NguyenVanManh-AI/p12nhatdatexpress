<div class="col-md-4">
    <div class="event-item">
        <div class="px-2 pt-2">
            <div class="image-ratio-box relative">
              <a
                class="absolute-full"
                href="{{ route('home.event.detail', $item->event_url) }}"
              >
                <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}" alt="">
              </a>
            </div>
        </div>

    <div class="content">
        <div class="event-date">
            <div class="event-bg-green">
                <div class="date">
                    <span>{{date('d', $item->start_date)}}</span>
                </div>
            </div>
        </div>
        <h3 class="title">
            <a class="{{$item->isHighLight() ? 'highlight' : '' }}" href="{{route('home.event.detail', $item->event_url)}}">{{$item->event_title}}</a>
        </h3>

        <div class="event-info">
            <div class="company item">
                <div class="icon">
                    <i class="fas fa-bookmark"></i>
                </div>
                <div class="info"><span>{{$item->bussiness->user_detail->fullname ?? ''}} </span>tổ chức</div>
            </div>

            <div class="event-time item">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info"><span>{{date('G:i', $item->start_date)}} </span>{{\App\Helpers\Helper::get_day_of_week($item->start_date)}}, {{date('d', $item->start_date)}} tháng {{date('m', $item->start_date)}}, {{date('Y', $item->start_date)}}</div>
            </div>

            <div class="location item">
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info"><span>{{$item->take_place}}</span></div>
            </div>
        </div>
    </div>

</div>
</div>
