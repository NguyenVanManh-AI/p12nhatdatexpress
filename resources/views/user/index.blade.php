@extends('user.layouts.master')
@section('title', 'Dashboard')

@section('content')
<x-user.breadcrumb
    active-label="Thống kê dữ liệu"
/>

<div class="user-statistics-page px-3 pb-3">
    <div class="card">
        <div class="card-body">
            <div class="statistics-page__overview">
                <div class="statistics-page__overview-item">
                    <div class="content-total-data data-customer">
                        <div class="icon-tatal-data">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="show-content-total-data">
                            <p>Số lượng khách hàng</p>
                            <h4 class="total-number">{{$customer['count']}}</h4>
                        </div>
                        <div class="develop-data">
                            <p class="count-data">+{{$customer['count_current']}}</p>
                            <p class="compare-month"><i class="fas fa-caret-{{$customer['percent'] < 0 ? 'down reduction' : 'up increase'}}"></i> {{abs($customer['percent'])}}% so với tháng trước</p>
                        </div>
                    </div>
                </div>
                <div class="statistics-page__overview-item">
                    <div class="content-total-data data-rating">
                        <div class="icon-tatal-data">
                            <img src="/frontend/images/rating-commnet-1.png" alt="">
                        </div>
                        <div class="show-content-total-data">
                            <p>Đánh giá</p>
                            <h4 class="total-number">{{$rating['count']}}</h4>
                        </div>
                        <div class="develop-data">
                            <p class="count-data">+{{$rating['count_current']}} tin</p>
                            <p class="compare-month"><i class="fas fa-caret-{{$rating['percent'] < 0 ? 'down reduction' : 'up increase'}}"></i> {{abs($rating['percent'])}}% so với tháng trước</p>
                        </div>
                    </div>
                </div>
                <div class="statistics-page__overview-item">
                    <div class="content-total-data data-comment">
                        <div class="icon-tatal-data">
                            <i class="far fa-comment-dots"></i>
                        </div>
                        <div class="show-content-total-data">
                            <p>Số Bình luận</p>
                            <h4 class="total-number">{{$comment['count']}}</h4>
                        </div>
                        <div class="develop-data">
                            <p class="count-data">+{{$comment['count_current']}} tin</p>
                            <p class="compare-month"><i class="fas fa-caret-{{$comment['percent'] < 0 ? 'down reduction' : 'up increase'}}"></i> {{abs($comment['percent'])}}% so với tháng trước</p>
                        </div>
                    </div>
                </div>
                <div class="statistics-page__overview-item">
                    <div class="content-total-data data-posts">
                        <div class="icon-tatal-data">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="show-content-total-data">
                            <p>Tổng số tin đăng</p>
                            <h4 class="total-number">{{$classified['count']}}</h4>
                        </div>
                        <div class="develop-data">
                            <p class="count-data">+{{$classified['count_current']}} tin</p>
                            <p class="compare-month"><i class="fas fa-caret-{{$classified['percent'] < 0 ? 'down reduction' : 'up increase'}}"></i> {{abs($classified['percent'])}}% so với tháng trước</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-type-buy">
                <div class="owl-carousel owl-custom-theme owl-hover-nav owl-nav-small owl-drag user-statistics__group-carousel">
                    @foreach($groups as $item)
                    <div
                        class="group-carousel__item image-ratio-box relative"
                        style="background-image:url({{ asset(data_get($item, 'image_url') ?: '/frontend/images/home/image_default_nhadat.jpg') }})"
                    >
                        <div class="absolute-full c-overlay"></div>
                        <div class="group-carousel__content absolute-full text-white d-flex flex-column justify-content-center align-items-center">
                            <h5 class="text-capitalize">{{ data_get($item, 'group_name') }}</h5>
                            <span class="group-carousel__total-buy">
                                ({{ data_get($item, 'count') }})
                            </span>
                            <div class="group-carousel__footer flex-start">
                                <small class="mr-3">
                                    {{ data_get($item, 'count_current') }}
                                </small>
                                <small>
                                    <i class="fas fa-caret-{{ data_get($item, 'percent') < 0 ? 'down text-success' : 'up text-danger'}}"></i>
                                    {{ abs($item['percent']) }}% so với tháng trước
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="title-total-manage-posts">
                        <p>Tin đăng nhiều lượt xem nhất</p>
{{--                        <span>-</span>--}}
{{--                        <select class="form-select get-date-post-data" aria-label=".form-select-sm example">--}}
{{--                            <option value="1" selected>Trong 1 tháng</option>--}}
{{--                            <option value="2">Trong 1 tuần </option>--}}
{{--                            <option value="3">Trong 1 ngày </option>--}}
{{--                        </select>--}}
                    </div>
                    <div class="content-total-manage-posts">
                        <div class="list-items-total-manage-posts">
                            <div class="list-break-layout-post">
                                @foreach($classified_view as $item)
                                <div class="item-total-manage-posts">
                                    <div class="avater-total-manage-posts">
                                        <img class="object-cover" src="{{$item->getThumbnailUrl()}}" alt="">
                                    </div>
                                    <div class="detai-total-manage-posts flex-1">
                                        <div class="name-detai-total-manage-posts d-flex align-items-end">
                                            <span class="text-ellipsis ellipsis-2 flex-1 word-break-all mw-mc">
                                                {{ $item->classified_name }}
                                            </span>

                                            @if($item->isShow())
                                                <a href="{{ $item->getShowUrl() ?: 'javascript:void(0);' }}" target="_blank">
                                                    (Xem tin)
                                                </a>
                                            @endif
                                        </div>
                                        <div class="list-bottom-detail-manage-post">
                                            <div class="item-bottom-detail-manage-post"><i class="far fa-clock"></i> {{\App\Helpers\Helper::get_time_to($item->created_at)}}</div>
                                            <div class="item-bottom-detail-manage-post"><i class="fas fa-signal"></i> {{$item->num_view}} lượt xem</div>
                                            <div class="item-bottom-detail-manage-post"><i class="fas fa-share"></i> {{$item->num_share}} chia sẻ</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="title-total-manage-posts">
                        <p>Tin đăng hiệu qủa nhất </p>
{{--                        <span>-</span>--}}
{{--                        <select class="form-select get-date-post-data" aria-label=".form-select-sm example">--}}
{{--                            <option value="1" selected>Trong 1 tháng</option>--}}
{{--                            <option value="2">Trong 1 tuần </option>--}}
{{--                            <option value="3">Trong 10 ngày</option>--}}
{{--                        </select>--}}
                    </div>
                    <div class="content-total-manage-posts">
                        <div class="list-items-total-manage-posts">
                            <div class="list-break-layout-post">
                                @foreach($classified_cus as $item)
                                    <div class="item-total-manage-posts">
                                        <div class="left-total-manage-posts">
                                            <div class="avater-total-manage-posts">
                                                <img class="object-cover" src="{{$item->getThumbnailUrl()}}" alt="">
                                            </div>
                                        </div>
                                        <div class="detai-total-manage-posts flex-1">
                                            <div class="name-detai-total-manage-posts d-flex">
                                                <span class="text-ellipsis ellipsis-2 flex-1 word-break-all mw-mc">
                                                    {{ $item->classified_name }}
                                                </span>
                                                @if($item->isShow())
                                                    <a href="{{ $item->getShowUrl() ?: 'javascript:void(0);' }}" target="_blank">
                                                        (Xem tin)
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="list-bottom-detail-manage-post">
                                                <div class="item-bottom-detail-manage-post"><i class="far fa-clock"></i> {{\App\Helpers\Helper::get_time_to($item->created_at)}}</div>
                                                <div class="item-bottom-detail-manage-post"><i class="fas fa-signal"></i> {{$item->customer}} số khách hàng</div>
                                                <div class="item-bottom-detail-manage-post"><i class="fas fa-share"></i> {{$item->num_share}} chia sẻ</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="title-total-manage-posts">
                        <p>Bình luận nổi bật nhất</p>
                    </div>
                    <div class="content-total-manage-posts">
                        <div class="list-items-total-manage-posts">
                            <div class="list-break-layout-post">
                                @foreach($classified_comment as $item)
                                <div class="item-total-manage-posts">
                                    <div class="left-total-manage-posts"> 
                                        <div class="avatar-comment-manage-post">
                                            <div class="avater-total-manage-posts">
                                                <img class="object-cover" src="{{ asset($item->user->getExpertAvatar()) }}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="detai-total-manage-posts flex-1">
                                        {{-- <div class="rating-comment-manage-posts"><img src="/frontend/images/rating-commnet-1.png" alt=""></div> --}}
                                        <div class="comment-detai-total-manage-posts d-flex">
                                            <span class="text-ellipsis ellipsis-2 flex-1 word-break-all mw-mc">
                                                {{ $item->comment_content }}
                                            </span>
                                        </div>
                                        <div class="list-bottom-detail-manage-post">
                                            <div class="item-bottom-detail-manage-post"><i class="far fa-clock"></i> {{\App\Helpers\Helper::get_time_to($item->created_at)}}</div>
                                            @if($item->classified && $item->classified->isShow())
                                                <div class="item-bottom-detail-manage-post">
                                                    <i class="fas fa-eye"></i>
                                                    <a href="{{ $item->classified->getShowUrl() ?: 'javascript:void(0);' }}" target="_blank">
                                                        Xem chi tiết
                                                    </a>
                                                </div>
                                                <div class="item-bottom-detail-manage-post">
                                                    <i class="fas fa-comment"></i>
                                                    <a href="{{ $item->classified->getShowUrl() ?: 'javascript:void(0);' }}" target="_blank">
                                                        Trả lời
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="title-total-manage-posts">
                        <p>Quảng cáo gần đây</p>
                    </div>
                    <div class="content-total-manage-posts">
                        <div class="list-items-total-manage-posts">
                            <div class="list-break-layout-post">
                                @foreach($banner as $item)
                                <div class="item-total-manage-posts">
                                    <div class="avater-total-manage-posts">
                                        <img class="object-cover" src="{{asset($item->image_url ?? 'frontend/images/home/image_default_nhadat.jpg')}}" alt="">
                                    </div>
                                    <div class="detai-advertisement-manage-posts flex-1">
                                        <div class="name-detai-total-manage-posts d-flex">
                                            <span class="text-ellipsis ellipsis-2 flex-1 word-break-all mw-mc">
                                                {{ $item->banner_title }}
                                            </span>
                                            <a href="{{ route('user.express') }}" target="_blank">
                                                (Xem banner)
                                            </a>
                                        </div>
                                        <div class="list-bottom-detail-manage-post">
                                            <div class="item-bottom-detail-manage-post"><i class="far fa-clock"></i> {{\App\Helpers\Helper::get_time_to($item->created_at)}}</div>
                                            <div class="item-bottom-detail-manage-post"><i class="fas fa-signal"></i> {{$item->num_view ?? '0'}}  lượt xem</div>
                                            <div class="item-bottom-detail-manage-post"><i class="fas fa-hand-pointer"></i> {{$item->num_click}}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
