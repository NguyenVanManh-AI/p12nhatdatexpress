@foreach($userFollowing as $item)
    <div class="item">
        <div class="avatar">
            <a href="{{route('trang-ca-nhan.dong-thoi-gian',$item->user_code)}}">
                <img class="object-fit-contain rounded-circle border w-100 h-100" src="{{asset($item->user_detail->image_url??SystemConfig::logo())}}" alt="">
            </a>
        </div>
        <div class="info">
            <h4 class="name" style="color:#3f3f3f">
                <a href="{{route('trang-ca-nhan.dong-thoi-gian',$item->user_code)}}">{{data_get($item->user_detail, 'fullname')}}</a>
            </h4>
            <p class="count">{{$item->followings_count}} Lượt theo dõi</p>
        </div>
    </div>
@endforeach