@foreach($userTopFollow as $item)
    <div class="item">
        <div class="avatar">
            <a href="{{route('trang-ca-nhan.dong-thoi-gian',$item->user_code)}}">
                <img class="object-fit-contain rounded-circle border w-100 h-100" src="{{asset($item->image_url??SystemConfig::logo())}}" alt="">
            </a>
        </div>
        <div class="info">
            <h4 class="name"><a href="{{route('trang-ca-nhan.dong-thoi-gian',$item->user_code)}}">{{$item->fullname}}</a></h4>
            <p class="count">{{$item->count}} Lượt theo dõi</p>
        </div>
    </div>
@endforeach