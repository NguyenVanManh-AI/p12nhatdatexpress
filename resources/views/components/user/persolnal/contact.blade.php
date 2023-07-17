<div class="info-titles">
    <h4>Thông tin liên hệ</h4>
</div>
<div class="info-contents">
    <div class="list-item">
        <div class="list-contact">
            <div class="item">
                <x-user.phone-number :user="$item" class="link-flat">
                    <x-slot name="icon">
                        <div class="icon c-mr-15">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                    </x-slot>
                </x-user.phone-number>
            </div>

            {{-- <div class="item">
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="info">
                    Công ty Địa Ốc Lâm Thủy Mộc
                </div>
            </div> --}}
        </div>
        <div class="list-infor">
            @if($item->user_location)
            <div class="item">
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info">
                    {{ data_get($item->user_location, 'full_address') }}
                </div>
            </div>
            @endif
        </div>
    </div>

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
</div>
