<div class="modal fade" id="add-classified__login-popup-modal" tabindex="-1" role="dialog"
  aria-labelledby="add-classified__login-popup-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content js-need-toggle-active">
      <div class="modal-header flex-center bg-sub-main">
        <img src="{{ asset('frontend/images/site-logo.png') }}">

        <button type="button" class="close custom-rounded-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body bg-gray-f5">
        <div class="row add-classified__login-action mb-3">
          <div class="col-6">
            <div class="add-classified__login-switch js-toggle-active js-switch-active active flex-center flex-column">
              <img class="mb-3" src="{{ asset('frontend/images/quick-logo.png') }}">
              <div
                class="btn add-classified__login-button text-blue px-0 py-1"
              >
                Đăng nhanh
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="add-classified__login-switch js-toggle-active js-switch-active flex-center flex-column">
              <img class="mb-3" src="{{ asset('frontend/images/login-logo.png') }}">
              <div
                class="btn add-classified__login-button text-blue px-0 py-1"
              >
                Đăng nhập đăng tin
              </div>
            </div>
          </div>
        </div>

        <div class="card js-toggle-area">
          <div class="card-body py-2">
            <ul class="active-hide list-slash">
              <li class="pl-2">Không thể chỉnh sửa tin đăng</li>
              <li class="pl-2">Không có chức năng thống kê dữ liệu</li>
              <li class="pl-2">Không có chức năng quản lý khách hàng</li>
              <li class="pl-2">Không có chức năng CSKH & Email Marketing</li>
            </ul>
            <ul class="active-show list-slash">
              <li class="pl-2">Đăng tin nâng cao</li>
              <li class="pl-2">Thống kê dữ liệu</li>
              <li class="pl-2">Hỗ trợ chức năng quản lý khách hàng</li>
              <li class="pl-2">Hỗ trợ chức năng CSKH</li>
            </ul>
          </div>
        </div>
        <div class="flex-center js-toggle-area">
          <a class="btn btn-light-cyan text-uppercase active-hide bold py-1 px-4" href="{{ route('guest.add-classified', 'nha-dat-ban') }}">
            Đăng tin
          </a>
          {{-- <button
            class="btn btn-light-cyan js-open-guest-add-classified text-uppercase active-hide bold py-1 px-4"
          >
            Đăng tin
          </button> --}}
          <button
            class="btn btn-light-cyan js-open-login text-uppercase active-show bold py-1 px-4"
            data-url="{{ route('user.add-classified', 'nha-dat-ban') }}"
            data-dismiss="modal"
          >
            Đăng nhập
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
