@extends('user.layouts.master')
@section('title', 'Tin khuyến mãi')

@section('content')
    <x-user.breadcrumb
        active-label="Tin khuyến mãi"
    />

    <div class="list-offer promotion-page px-3 pb-3">
        <div class="owl-carousel owl-custom-theme owl-hover-nav owl-drag promotion-page__banner-carousel mb-5">
            {{-- should get data not hardcode --}}
            @for($i = 0; $i < 5; $i++)
                <div class="image-ratio-box-half relative">
                    <div class="absolute-full">
                        <img class="object-cover" src="{{ asset('frontend/images/managerEmployee/offer.png') }}" alt="">
                    </div>
                </div>
            @endfor
        </div>

        @foreach($promotion_news as $item)
            <div class="item">
                <div class="offer-day">
                    <span>{{vn_date($item->date_from)}}</span>
                </div>
                <div class="offer-info">
                    <div class="offer-image flex-center pt-3 pb-4">
                        <div class="promotion-page-background" style="background-image: url({{asset($item->image ? '/system/images/post_promotion/' . $item->image : 'frontend\images\home\image_default_nhadat.jpg')}})">
                            {{-- <img src="{{asset($item->image ? '/system/images/post_promotion/' . $item->image : 'frontend\images\home\image_default_nhadat.jpg')}}" alt=""> --}}
                        </div>
                    </div>
                    <div class="offer-content pt-3 pb-4">
                        <h3 class="offer-title fs-18">{{$item->news_title}}</h3>
                        <div class="offer-desc"><i>{{$item->news_description}}</i><br>{!! $item->news_content!!}</div>
                    </div>
                    <div class="offer-getCode">
                        @if($item->promotion_code)
                            <a href="{{route('user.promotion-receipt', $item->promotion_code)}}">Nhận mã</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="table-pagination">
        <div class="left"></div>
        <div class="right">
            {{ $promotion_news->render('user.page.pagination') }}
        </div>
    </div>

@endsection
