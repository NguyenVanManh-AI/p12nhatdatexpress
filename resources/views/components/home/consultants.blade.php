<div class="widget widget-height widget-contact mobile-not-hide c-mb-10">
    <div class="widget-title">
        <h3 class="text-center fs-16">Chuyên viên tư vấn</h3>
    </div>
    <div class="widget-height widget-content">
        <div class="add-event">
            <x-common.accept-location
                enable-text="Vị trí của tôi"
                disable-text="Xóa vị trí"
                class="bold rounded py-1 fw-normal fs-14"
                enable-btn-class="btn-success"
            />

            <p>Cần xác nhận vị trí để hiển thị chính xác thông tin</p>
        </div>
        <div class="list-event mb-2">
            @foreach ($consultant as $user)
                <div class="item mb-2">
                    <div class="avatar">
                        <a href="{{route('trang-ca-nhan.dong-thoi-gian', $user->user_code)}}">
                            <img class="object-cover lazy" data-src="{{ $user->getExpertAvatar() }}" alt="">
                        </a>
                    </div>
                    <div class="content">
                        <h3 class="title">
                            <a
                                href="{{ route('trang-ca-nhan.dong-thoi-gian', $user->user_code) }}"
                                class="bold {{ $user->is_highlight ? 'link-red-flat' : 'link' }}"
                            >
                                {{ $user->getFullName() }}
                            </a>
                        </h3>
                        <span class="location text-light-cyan">
                            {{ data_get($user->location, 'province.province_name') }}
                        </span>
                        <x-user.phone-number :user="$user" class="link-flat phone__copy-small text-blue bold flex-start">
                            <x-slot name="icon">
                              <div class="text-blue mr-2">
                                <i class="fas fa-phone-alt"></i>
                              </div>
                            </x-slot>
                        </x-user.phone-number>
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
            <a class="text-info" href="{{ route('home.danh-ba.chuyen-vien-tu-van') }}"><span><i class="fas fa-angle-double-right mr-2"></i></span>Xem hết</a>
        </div>
    </div>
</div>
