<div class="profile-admin">
    <div class="cover-image">
        <img src="{{ $item->getBackGroundUrl() }}" alt="">
        <div class="list-feature">
            <div class="item bg-white">
                <div class="icon">
                    <i class="far fa-clock"></i>
                </div>
                <div class="name">
                    <a href="{{route('trang-ca-nhan.dong-thoi-gian',$item->user_code)}}">Dòng thời gian</a>
                </div>
            </div>

            <div class="item bg-white">
                <div class="icon">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div class="name">
                    <a href="{{route('trang-ca-nhan.danh-sach-tin',$item->user_code)}}">Danh sách tin</a>
                </div>
            </div>
            <div class="item bg-white reviews">
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="name">
                    <a href="{{route('trang-ca-nhan.danh-gia',$item->user_code)}}">Đánh giá</a>
                </div>
            </div>
            @if(!$item->isEnterprise() && $item->id != auth()->guard('user')->id())
            <div class="item follow-user btn-cyan" data-id="{{$item->id}}">
                @php
                $check_follow = \App\Models\User\UserPostFollow::where([
                    'user_id'=>auth()->guard('user')->id(),
                    'follow_id'=>$item->id,
                ])->first();
                @endphp
                <div class="icon">
                    <i class="fas fa-rss"></i>
                </div>
                <div class="name">
                    {{$check_follow?"Đang theo dõi":"Theo dõi"}}
                </div>
            </div>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{request()->url()}}" target="_blank" class="text-white">
                <div class="item btn-success dropdown">
                    <div class="item m-0 p-0" data-toggle="dropdown">
                        <div class="icon">
                            <i class="fas fa-share"></i>
                        </div>
                        <div>
                            Chia sẻ
                        </div>
                    </div>
                </div>
            </a>
            @endif
        </div>
    </div>
</div>
