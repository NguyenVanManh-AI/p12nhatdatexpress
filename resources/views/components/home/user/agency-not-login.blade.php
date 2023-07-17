<div class="user-detail-box sticky top-3 js-need-toggle-active">
  <x-common.loading class="inner send-advisory-loading small"/>

  <div class="pt-2 px-3 border user-detail__inner scrollable scrollbar-hidden">
    <div class="user-detail__header text-center mb-3">
      <div class="mb-2 pt-2">
        <x-user.avatar
          width="90"
          height="90"
          rounded="45"
          :is-fancy-box="false"
          avatar="{{ asset('frontend/images/no_avatar.png') }}"
        />
      </div>

      <h5 class="text-success fs-18 w-100 mb-0">
        <span class="text-ellipsis w-100">
          {{ $item->contact_name }}
        </span>
      </h5>
    </div>
    <div class="user-detail__body js-toggle-area">
      <div class="active-hide">
        <div class="user-detail-body__item mb-3 text-gray text-break">
          <img src="{{ asset('frontend/images/grey-circle-map-marker.png') }}" alt="">
          {{ $item->contact_address }}
        </div>
        @if($item->contact_email)
          <div class="user-detail-body__item mb-3 text-gray text-break">
            <img src="{{ asset('frontend/images/grey-circle-mail.png') }}" alt="">
            <x-common.mail mail="{{ $item->contact_email }}" />
          </div>
        @endif
        @if ($item->contact_phone)
          <div class="user-detail-body__item mb-3">
            <x-user.phone-number :phone="$item->contact_phone" class="text-gray" />
          </div>
        @endif
      </div>
      @if($advisoryUrl)
        <div class="active-show">
          @include('components.home.user.partials._advisory-form', [
              'user' => $item,
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
        <div class="user-detail__footer-box {{ $item->contact_email ? 'flex-end' : 'flex-center' }}">
          @if($advisoryUrl && $item->contact_email)
            <a class="btn btn-danger px-2 user-detail__footer-button js-toggle-active" title="Tư vấn"
              href="javascript:void(0)">
              <i class="fas fa-edit"></i>
              <span class="user-detail__footer-button-text">
                Tư vấn
              </span>
            </a>
          @endif
          <a class="btn btn-light-cyan px-2 user-detail__footer-button" title="Chia sẻ"
            href="{{ share_fb(url()->current()) }}" target="_blank">
            <i class="fas fa-share"></i>
            <span class="user-detail__footer-button-text">
              Chia sẻ
            </span>
          </a>
        </div>
      </div>
      @if($advisoryUrl)
        <div class="active-show">
          <div class="user-detail__footer-box is-grid">
            <a class="btn btn-secondary px-2 user-detail__footer-button js-toggle-active" title="Quay về" href="javascript:void(0)">
              <i class="fas fa-arrow-left mr-1"></i>
              <span class="user-detail__footer-button-text">
                Quay về
              </span>
            </a>
            <a class="btn btn-danger px-2 user-detail__footer-button js-send-advisory" title="Gửi" href="javascript:void(0)">
              <i class="far fa-paper-plane"></i>
              <span class="user-detail__footer-button-text">
                Gửi
              </span>
            </a>
            <a class="btn btn-light-cyan px-2 user-detail__footer-button" title="Chia sẻ" href="{{ share_fb(url()->current()) }}" target="_blank">
              <i class="fas fa-share"></i>
              <span class="user-detail__footer-button-text">
                Chia sẻ
              </span>
            </a>
          </div>
        </div>
      @endif
    </div>
  @endif
</div>
