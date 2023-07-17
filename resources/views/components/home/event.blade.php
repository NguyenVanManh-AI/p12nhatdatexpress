<div class="widget widget-height widget-event c-mb-10" style="">
    <div class="widget-title">
        <h3 class="text-center">Sự kiện sắp diễn ra</h3>
    </div>
    <div class="widget-content">
        <div class="add-event">
            <a href="javascript:void(0);" class="btn-add-event" onclick="addEvent(event);"><i class="fas fa-plus-circle"></i> Sự kiện</a>
            <p>Doanh nghiệp có thể thêm sự kiện mới tại đây</p>
        </div>
        <div class="list-event mb-2">
            @foreach($events as $event)
                <div class="item mb-2">
                    <div class="thumbnail">
                        <a href="{{route('home.event.detail', $event->event_url)}}" >
                            <img class="lazy" data-src="{{$event->image_header_url}}" alt="">
                        </a>
                    </div>
                    <div class="content">
                        <h3 class="title text-break"><a class="{{$event->is_highlight ? 'highlight' : '' }}" href="su-kien/{{$event->event_url}}">{{$event->event_title}}</a></h3>
                        <span class="location">
                            {{ $event->getLocationAddress() }}
                            {{-- {{$event->location->district->district_name}}, {{$event->location->province->province_name}} --}}
                         </span>
                        <span class="post-time">{{date('d/m/Y', $event->start_date)}}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="view-more-with-link text-center border-top pt-2">
            <a href="{{route('home.event.list')}}">Xem thêm</a>
        </div>
    </div>
</div>

@section('popup')
    <x-home.event.add-event />
@endsection
