    <div class="mb-4">
        <x-user.persolnal.info :item="$item"></x-user.persolnal.info>
    </div>

    @if(data_get($item->detail, 'intro'))
    <div class="info-introduce info-section rounded">
        <div class="info-title">
            <h4>Giới thiệu</h4>
        </div>
        <div class="info-content">
            <div class="info-desc">
                <div class="excerpt text-break">
                    {{ data_get($item->detail, 'intro') }}
                </div>
                @if(strlen(data_get($item->detail, 'intro')) >35)
                    <span class="show-hide">Xem thêm &gt;&gt;</span>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="info-section rounded">
        <div class="info-title">
            <h4>Thông tin liên hệ</h4>
        </div>
        <div class="info-content">
            <div class="list-contact mb-3">
                <div class="item">
                    <x-user.phone-number :user="$item" class="link-flat">
                        <x-slot name="icon">
                            <div class="icon c-mr-15">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                        </x-slot>
                    </x-user.phone-number>
                </div>

                {{-- @if($item->email)
                <div class="item">
                    <div class="icon c-mr-15">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info">
                        <a href="mailto:{{$item->email}}">{{$item->email}}</a>
                    </div>
                </div>
                @endif --}}
                @if($item->user_location)
                <div class="item">
                    <div class="icon c-mr-15">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info">
                        {{ data_get($item->user_location, 'full_address') }}
                    </div>
                </div>
                @endif
{{--                <div class="item">--}}
{{--                    <div class="icon c-mr-15">--}}
{{--                        <i class="fas fa-briefcase"></i>--}}
{{--                    </div>--}}
{{--                    <div class="info">--}}
{{--                        Công ty Địa Ốc Lâm Thủy Mộc--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <div class="icon c-mr-15">--}}
{{--                        <i class="fas fa-map-marker-alt"></i>--}}
{{--                    </div>--}}
{{--                    <div class="info">--}}
{{--                        245 Hải Phòng, Thanh Khê, Đà Nẵng--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <div class="icon c-mr-15">--}}
{{--                        <i class="fas fa-briefcase"></i>--}}
{{--                    </div>--}}
{{--                    <div class="info">--}}
{{--                        Công ty Địa Ốc Lâm Thủy Mộc--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
{{--            <div class="show-more">--}}
{{--                <a href="#">Hiện thêm</a>--}}
{{--            </div>--}}

            <?php
                $socials = [
                    [
                        'type' => 'facebook',
                        'link' => data_get($item->detail, 'facebook'),
                        'icon' => 'fab fa-facebook-f',
                    ],
                    [
                        'type' => 'youtube',
                        'link' => data_get($item->detail, 'youtube'),
                        'icon' => 'fab fa-youtube',
                    ],
                    [
                        'type' => 'twitter',
                        'link' => data_get($item->detail, 'twitter'),
                        'icon' => 'fab fa-twitter',
                    ],
                ];
            ?>

            <x-common.social-link
                class="flex-center"
                :socials="$socials"
            />

            {{-- <div class="list-social">
                <div class="item-social social-fb">
                    <a href="{{$item->user_detail->facebook??'javascript:{}'}}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                </div>
                <div class="item-social social-yt">
                    <a href="{{$item->user_detail->youtube??'javascript:{}'}}" target="_blank"><i class="fab fa-youtube"></i></a>
                </div>
                <div class="item-social social-tik bg-secondary">
                    <a href="{{$item->user_detail->twitter??'javascript:{}'}}" target="_blank"><i class="fab fa-tiktok"></i></a>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="info-statistic info-section rounded">
        <div class="info-title">
            <h4>Thống kê</h4>
        </div>
        <div class="info-content">
            <div class="list-statistic">
                <div class="item">
                    <div class="icon c-mr-15">
                        <i class="fas fa-rss"></i>
                    </div>
                    <div class="info">
                        Có: &nbsp;   <span class="text-bold">{{$item->follow->count()}} người</span>  &nbsp;  theo dõi
                    </div>
                </div>
                <div class="item">
                    <div class="icon c-mr-15">
                        <i class="far fa-eye"></i>
                    </div>
                    <div class="info">
                        Tổng số lượt xem: {{$item->num_view}}
                    </div>
                </div>
                <div class="item">
                    <div class="icon c-mr-15">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="info">
                        Lượt xem trong ngày: {{$item->num_view_today}}
                    </div>
                </div>
                <div class="item">
                    <div class="icon c-mr-15">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="info">
                        Số tin đã đăng: {{$item->classified->count()}}
                    </div>
                </div>
                <div class="item">
                    <div class="icon c-mr-15">
                        <i class="far fa-file-alt"></i>
                    </div>
                    <div class="info">
                        Số lượt đánh giá: {{$item->rating}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="info-warning rounded">
        <div class="warning-desc">
            Đăng nội dung, hình ảnh hoặc bình luận không phù hợp sẽ bị khóa tài khoản vĩnh viễn.
        </div>
        <div class="text-right">
            <a href="#" class="regulation">Xem quy định</a>
            <a href="#" data-id="{{$item->id}}" class="report-user report-persolnal">Báo cáo <i class="fas fa-exclamation-circle"></i></a>
        </div>
    </div>
