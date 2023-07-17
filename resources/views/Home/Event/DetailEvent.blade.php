@extends('Home.Layouts.Master')
@section('Title',$event->meta_title??$event->event_title)
@section('Keywords',$event->meta_key??$event->event_title)
@section('Description',$event->meta_desc??$event->event_content)
@section('Image',asset($event->image_header_url??'frontend/images/home/image_default_nhadat.jpg'))
@section('Content')
    <div id="event-single" class="event-page">
        <div class="event-banner__box mb-4" style="background-image: url({{ $event->getImageUrl() }})">
            <div class="event-banner__inner">
                <div class="event-banner__title-box">
                    <div class="event-banner__calendar position-absolute">
                        <div class="event-banner__calendar-inner relative">
                            <div class="event-banner__calendar-image">
                                <img src="{{ asset('frontend/images/time.png') }}">
                            </div>
                            <span class="event-banner__calendar-date text-center position-absolute text-center w-100">
                                {{date('d', $event->start_date)}}
                            </span>
                            <span class="event-banner__calendar-month text-uppercase fs-normal fw-500 text-white text-center position-absolute text-center w-100">
                                {{ \DateTime::createFromFormat('!m', date('m', $event->start_date))->format('F') }}
                            </span>
                        </div>
                    </div>
                    <div class="event-banner__title-body text-white text-center">
                        <h3 class="event-banner__title-status">
                            {{ $event->getTitleStatus('vn') }}
                        </h3>
                        <h2 class="event-banner__main-title text-break">
                            {{ $event->event_title }}
                        </h2>
                        <p class="event-banner__title-time text-uppercase bold mb-0">
                            {{ \App\Helpers\Helper::get_day_of_week($event->start_date) }}, {{ date('d', $event->start_date) }} tháng {{ date('m', $event->start_date) }}, {{ date('Y', $event->start_date) }} lúc {{ date('G:i', $event->start_date) }} UTC+7
                        </p>
                        <p class="event-banner__title-place text-uppercase mb-0">
                            {{ $event->take_place }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pt-2">
            <div class="row">
                <div class="col-md-7-3 event-main">
                    <div class="event-main-content">
                        <div class="event-single-content">

                            <div class="single-meta">
                                <div class="left">
                                    <h5 class="detail-big-title text-break">
                                        {{ $event->getTitleStatus() }}
                                    </h5>
                                </div>
                                <div class="right">
                                    {{-- <span class="share-button">
                                        <i class="fas fa-share-alt"></i>Chia sẻ
                                        <div class="share-sub">
                                            <a class="item">
                                                <i class="fab fa-facebook-square"></i>
                                                Facebook
                                            </a>
                                            <a class="item">
                                                <i class="fab fa-twitter-square"></i>
                                                Twitter
                                            </a>
                                            <a class="item">
                                                <i class="fab fa-pinterest-square"></i>
                                                Pinterest
                                            </a>
                                            <a class="item">
                                                <i class="zalo-icon"></i>
                                                Zalo
                                            </a>
                                        </div>
                                    </span> --}}
                                    <span class="report-button report-content" data-id="{{$event->id}}">
                                        <i class="fas fa-exclamation"></i>Báo cáo
                                    </span>
                                    <span class="view-count">
                                        <i class="fas fa-chart-bar"></i>Lượt xem: <span>{{$event->num_view ?? 0}}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="single-contact-buttons">
                                <a href="#" class="green">
                                    <i class="fas fa-phone-alt"></i>
                                    Liên hệ
                                </a>
                                <a href="#" class="blue">
                                    <i class="fas fa-user"></i>
                                    Đăng ký thông tin
                                </a>
                            </div>
                            <div class="event-detail">
                                <span class="text-pre-wrap">
                                    {!! $event->event_content !!}
                                </span>

                                <img src="{{ asset($event->image_invitation_url) }}" alt="">

                                <div class="event-section">
                                    <div class="head">Chi tiết sự kiện</div>
                                    <div class="content">
                                        <div class="item">
                                            <i class="fas fa-bookmark"></i>
                                            <span><strong>{{$event->bussiness->user_detail->fullname ?? ''}} </strong>&nbsptổ chức</span>
                                        </div>
                                        <div class="item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{\App\Helpers\Helper::get_day_of_week($event->start_date)}}, {{date('d', $event->start_date)}} tháng {{date('m', $event->start_date)}}, {{date('Y', $event->start_date)}} lúc {{date('G:i', $event->start_date)}} UTC+7</span>
                                        </div>
                                        <div class="item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><strong>{{$event->take_place}}</strong>&nbsp- {{ data_get($event->location, 'address') . ', ' }} {{ $event->getLocationAddress() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map -->
                                <x-home.map-embed :mapLongtitude="data_get($event->location, 'map_longtitude')" :mapLatitude="data_get($event->location, 'map_latitude')"></x-home.map-embed>
                                <!-- //Map -->

                                {{-- <x-home.rating :rate="$event->rating" :ip="$ip"></x-home.rating> --}}
                                <div class="project-review">
                                    <div class="head">
                                        <i class="fas fa-user-circle"></i>
                                        Đánh giá
                                    </div>

                                    <div class="detail-review-result-box" data-url="/events/rating/{{ $event->id }}">
                                        <x-common.detail.review-result
                                            :item="$event"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3-7 project-sidebar">
                    {{-- <x-home.author-info :author="$event->bussiness" /> --}}
                    @if($event->user)
                        <x-home.user.agency-detail
                            :user="$event->user"
                            advisory-url="{{ route('home.events.send-advisory', $event->id) }}"
                            advisory-label="Tư vấn"
                        />
                    @else
                        <x-home.user.agency-not-login
                            :item="$event"
                            advisory-label="Tư vấn"
                        />
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 project-recent">
                    <x-home.classified.current />
                </div>
            </div>
        </div>
    </div>
    <x-home.report-modal></x-home.report-modal>
@endsection
@section('Script')
    <script>
        $('.report-content').click(function(){
            $('#report_content').show();
            $('#layout').show();
            $('#report_content').find('form').attr('action','{{route('home.event.report-content','')}}'+'/'+$(this).data('id'));
            $("body").on("click", "#layout", function (event) {
                $('#report_content').hide();
            });
        });
    </script>
@endsection

