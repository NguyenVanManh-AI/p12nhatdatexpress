<div class="status-account-done">
  {{-- should check and improve all --}}
  <div class="title-status-account-{{ $user_info || !$user->isActive() ? 'update' : 'done' }}">
    <h5>Tình trạng</h5>
  </div>
  @if ($user_info || !$user->isActive())
    <div class="content-error-update">
      <div class="list-error-update">
        @if (!$user->isActive())
          <div class="item-error-update">
            <span class="position-relative">
              {{ $user->getInfoStatus() }}
              <span class="position-absolute top-0 start-100 badge badge-pill badge-danger ml-2" data-title="{{ $user->getInfoNotice() }}">!</span>
            </span>
          </div>
        @endif
        @if ($user_info)
          @if ($user_info->phone_number == null)
            <div class="item-error-update">
              <span class="position-relative">
                Chưa cập nhật số điện thoại
                <span class="position-absolute top-0 start-100 badge badge-pill badge-danger ml-2">!</span>
              </span>
            </div>
          @endif
          @if ($user_info->email == null)
            <div class="item-error-update">
              <span class="position-relative">
                Chưa cập nhật email
                <span class="position-absolute top-0 start-100 badge badge-pill badge-danger ml-2">!</span>
              </span>
            </div>
          @endif
          @if ($user_info->province_id == null or $user_info->district_id == null or $user_info->ward_id == null)
            <div class="item-error-update">
              <span class="position-relative">
                Chưa cập nhật địa chỉ
                <span class="position-absolute top-0 start-100 badge badge-pill badge-danger ml-2">!</span>
              </span>
            </div>
          @endif
          @if ($user_info->is_forbidden == 1)
            <div class="item-error-update">
              <span class="position-relative">
                Tài khoản bạn đang bị cấm
                <span class="position-absolute top-0 start-100 badge badge-pill badge-danger ml-2">!</span>
              </span>
            </div>
          @endif
        @endif
      </div>
    </div>
  @else
    <div class="content-status-account-done">
      <i class="far fa-check-circle"></i>
    </div>
  @endif
</div>
