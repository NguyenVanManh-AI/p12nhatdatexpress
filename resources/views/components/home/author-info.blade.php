@if($author)
<div class="agency">
    <div class="wrapper">
        <div class="avatar">
            <img src="{{asset( $author->user_detail->image_url ??'frontend/images/no_avatar.png')}}">
        </div>
        <span class="name">{{$author->user_detail->fullname ?? ''}}</span>
{{--        <span class="job">Công ty tư vấn Bất động sản</span>--}}
        <span class="since">Thành viên từ tháng {{ date( 'm/Y', $author->created_at) }}</span>
        <div class="stars">
            <x-common.color-star class="star-curved" :stars="$author->rating"/>
        </div>
        <div class="info">
            <a href="#">Xem trang</a>
            <a href="#">Danh sách tin đăng</a>
        </div>
        <div class="meta">
            <div class="meta-item">
                <img src="{{asset('frontend/images/grey-circle-map-marker.png')}}" alt="">
                {{$author->user_location->address ? $author->user_location->address . ', ' : ''}} {{$author->user_location->ward->ward_name}}, {{$author->user_location->district->district_name}}, {{$author->user_location->province->province_name}}
            </div>

            @if($author->email)
            <div class="meta-item">
                <img src="{{asset('frontend/images/grey-circle-mail.png')}}" alt="">
                {{$author->email}}
            </div>
            @endif

            @if($author->user_detail->phone_number)
            <div class="meta-item">
                <img src="{{asset('frontend/images/grey-circle-phone.png')}}" alt="">
                <a href="tel:{{$author->user_detail->phone_number}}" class="font-weight-normal" style="color: #454545" tabindex="-1">
                    <span class="display-phone">{{\Illuminate\Support\Str::limit($author->user_detail->phone_number, 4, '')}}</span>
                </a>
                <span class="hide-phone" data-phone="{{$author->user_detail->phone_number}}">Hiện số</span>
            </div>
            @endif

            @if($author->user_detail->website)
            <div class="meta-item">
                <img src="{{asset('frontend/images/grey-circle-link.png')}}" alt="">
                <a href="{{$author->user_detail->website}}" target="_blank" class="font-weight-normal" style="color: #454545" tabindex="-1">
                    {{$author->user_detail->website}}
                </a>
            </div>
            @endif

        </div>
        <div class="social">
            @if($author->user_detail->facebook)<a href="{{$author->user_detail->facebook}}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
            @if($author->user_detail->twitter)<a href="{{$author->user_detail->twitter}}" target="_blank"><i class="fab fa-tiktok"></i></a>@endif
{{--            @if($author->user_detail->google)<a href="{{$author->user_detail->google}}" target="_blank"><i class="fab fa-google"></i></a>@endif--}}
{{--            @if($author->user_detail->dribbble)<a href="{{$author->user_detail->dribbble}}" target="_blank"><i class="fab fa-dribbble"></i></a>@endif--}}
        </div>
    </div>
    <div class="wrapper wrapper-basic">
        <div class="buttons">
            <div class="button green">
                <i class="fas fa-envelope"></i>
                Chat
            </div>
            <div class="button red">
                <i class="fas fa-user"></i>
                Nhận tư vấn
            </div>
            <div class="button blue">
                <i class="fas fa-share"></i>
                Chia sẻ
            </div>
        </div>
    </div>
</div>
@endif
