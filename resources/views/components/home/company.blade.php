<div class="widget widget-height widget-real-estate c-mb-10">
    <div class="widget-title">
        <h3 class="text-center">Công ty bất động sản</h3>
    </div>
    <div class="widget-content">
        <div class="list-event mb-2">
            @foreach ($companyUsers as $user )
            <div class="item mb-2">
                <div class="thumbnail">
                    <a href="{{route('trang-ca-nhan.dong-thoi-gian', $user->user_code)}}">
                        <img class="lazy" data-src="{{ $user->getExpertAvatar()  }}" alt="">
                    </a>
                </div>
                <div class="content">
                    <h3 class="title">
                        <a class="bold fs-16 {{ $loop->index == 0 ? 'link-red-flat' : 'link' }}" href="{{ route('trang-ca-nhan.dong-thoi-gian', $user->user_code) }}">
                            {{ $user->getFullName() }}
                        </a>
                    </h3>
                    <span class="location text-light-cyan fs-14">
                        {{ data_get($user->location, 'province.province_name') }}
                    </span>
                    <a class="text-success fs-14" href="{{ route('trang-ca-nhan.dong-thoi-gian', $user->user_code) }}" class="color-green">Xem trang doanh nghiệp</a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex-between">
            <div>
                @if (!auth()->guard('user')->check())
                    <a class="btn btn-primary btn-register btn-sm px-3" href="#">Đăng ký</a>
                @endif
            </div>
            <a class="text-info" href="{{ route('home.danh-ba.doanh-nghiep') }}"><span><i class="fas fa-angle-double-right mr-2"></i></span>Xem hết</a>
        </div>
    </div>
</div>
@section('Style')
<style>
    .bold-blue {
        background-color: #007cc9;
        height: 25px;
        width: 92px;
    }

    a {
        color: #007bff;
        text-decoration: none;
        background-color: transparent;
        text-align: center;
    }
</style>
@endsection
