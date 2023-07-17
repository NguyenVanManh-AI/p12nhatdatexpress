@if($user)
  <div class="user-detail-box sticky top-3 js-need-toggle-active">
    <x-common.loading class="inner send-advisory-loading small"/>

    <div class="pt-2 px-3 border user-detail__inner scrollable scrollbar-hidden">
      <div class="user-detail__header text-center mb-3">
        <div class="mb-2 pt-2">
          <x-user.avatar width="90" height="90" rounded="45" :is-fancy-box="false" avatar="{{ $user->getAvatarUrl() }}"
            frame="{{ $user->level ? $user->level->getImageUrl() : null }}" />
        </div>
        <h5 class="text-success fs-18 w-100 mb-0">
          <span class="text-ellipsis w-100">
            {{ data_get($user->detail, 'fullname') }}
          </span>
        </h5>
        <strong class="text-dark w-100 fs-14">
          {{ data_get($user->user_type, 'type_name') }}
        </strong>
        <p class="text-gray mb-0">
          Thành viên từ tháng {{ date('m/Y', $user->created_at) }}
        </p>
        <div class="mb-2">
          <x-common.color-star class="star-curved" :stars="$user->rating ?: 5" />
        </div>
        <div class="flex-center flex-wrap">
          <a href="{{ route('trang-ca-nhan.dong-thoi-gian', $user->user_code) }}"
            class="btn btn-sm btn-danger mx-1 mb-2 fs-12"
            target="_blank"
            title="Xem trang cá nhân"
          >
            Trang cá nhân
          </a>
          <a href="{{ route('trang-ca-nhan.danh-sach-tin', $user->user_code) }}"
            class="btn btn-sm btn-danger mx-1 mb-2 fs-12"
            title="Danh sách tin"
            target="_blank"
          >
            Danh sách tin
          </a>
        </div>
      </div>
      <div class="user-detail__body js-toggle-area">
        <div class="active-hide">
          @if (data_get($user->location, 'full_address'))
            <div class="user-detail-body__item mb-3 text-gray text-break">
              <img src="{{ asset('frontend/images/grey-circle-map-marker.png') }}" alt="">
              {{ data_get($user->location, 'full_address') }}
            </div>
          @endif
          @if ($user->phone_number)
            <div class="user-detail-body__item mb-3">
              <x-user.phone-number :user="$user" class="text-gray" />
            </div>
          @endif
          @auth('user')
            @if (data_get($user->detail, 'website'))
              <a
                href="{{ data_get($user->detail, 'website') }}"
                class="user-detail-body__item d-inline-block mb-3 link-flat text-break"
                target="_blank"
                rel="noopener noreferrer nofollow"
              >
                <img src="{{ asset('frontend/images/grey-circle-link.png') }}" alt="">
                {{ data_get($user->detail, 'website') }}
              </a>
            @endif
            <div class="user-detail-body__social-box flex-start flex-wrap mb-3">
              <?php
                  $socials = [
                      [
                          'type' => 'facebook',
                          'link' => data_get($user->detail, 'facebook'),
                          'icon' => 'fab fa-facebook-f',
                      ],
                      [
                          'type' => 'youtube',
                          'link' => data_get($user->detail, 'youtube'),
                          'icon' => 'fab fa-youtube',
                      ],
                      [
                          'type' => 'twitter',
                          'link' => data_get($user->detail, 'twitter'),
                          'icon' => 'fab fa-twitter',
                      ],
                  ];
              ?>

              <x-common.social-link
                :socials="$socials"
              />
            </div>
          @endauth
        </div>
        @if($advisoryUrl)
          <div class="active-show">
            @include('components.home.user.partials._advisory-form', [
                'user' => $user,
                'authUser' => auth('user')->user(),
                'advisory-url' => $advisoryUrl,
            ])
          </div>
        @endif
      </div>
    </div>

    @if (isset($footerAction) && $footerAction)
      {{ $footerAction }}
    @else
      <div class="py-2 px-3 border border-top-0 user-detail__footer js-toggle-area">
        <div class="active-hide">
          <div class="user-detail__footer-box @auth('user') is-grid @else flex-end @endauth">
            @auth('user')
            <a class="btn btn-success px-2 user-detail__footer-button" title="Chat" href="javascript:void(0)">
              <i class="fas fa-envelope"></i>
              <span class="user-detail__footer-button-text">
                Chat
              </span>
            </a>
            @endif

            <a class="btn btn-danger px-2 user-detail__footer-button {{ $advisoryUrl ? 'js-toggle-active' : '' }}" title="{{ $advisoryLabel }}"
              href="javascript:void(0)">
              <i class="fas fa-edit"></i>
              <span class="user-detail__footer-button-text">
                {{ $advisoryLabel }}
              </span>
            </a>
            <a class="btn btn-light-cyan px-2 user-detail__footer-button" title="Chia sẻ"
              href="{{ share_fb(url()->current()) }}" target="_blank">
              <i class="fas fa-share"></i>
              <span class="user-detail__footer-button-text">
                Chia sẻ
              </span>
            </a>
          </div>
        </div>
        <div class="active-show">
          <div class="user-detail__footer-box is-grid">
            <a class="btn btn-secondary px-2 user-detail__footer-button js-toggle-active" title="Quay về" href="javascript:void(0)">
              <i class="fas fa-arrow-left mr-1"></i>
              <span class="user-detail__footer-button-text">
                Quay về
              </span>
            </a>
            @if($advisoryUrl)
              <a class="btn btn-danger px-2 user-detail__footer-button js-send-advisory" title="Gửi" href="javascript:void(0)">
                <i class="far fa-paper-plane"></i>
                <span class="user-detail__footer-button-text">
                  Gửi
                </span>
              </a>
            @endif
            <a class="btn btn-light-cyan px-2 user-detail__footer-button" title="Chia sẻ" href="{{ share_fb(url()->current()) }}" target="_blank">
              <i class="fas fa-share"></i>
              <span class="user-detail__footer-button-text">
                Chia sẻ
              </span>
            </a>
          </div>
        </div>
      </div>
    @endif
  </div>
@endif
