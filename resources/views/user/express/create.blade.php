@extends('user.layouts.master')
@section('title', 'Tạo chiến dịch')

@section('content')
<div class="py-3">
  <div class="user-create-express-page">
    <form action="{{route('user.post-express')}}" method="post" enctype="multipart/form-data" class="user-express-form">
      @csrf
      <div class="step-section step-1">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Quảng cáo Express</a></li>
          <li class="breadcrumb-item"><a href="#">Vị trí</a></li>
        </ol>

        <div class="p-3">
          <div class="banner-box d-flex flex-wrap mb-3">
            <h5 class="banner-title fs-18">Chọn vị trí quảng cáo:</h5>
            <label class="c-form-check mr-4">
              Trang chuyên mục
              <input type="radio" name="banner_group" data-text="Trang chuyên mục" value="C" {{ old('banner_group') != 'H' ? 'checked' : '' }} class="c-form-check-input">
              <span class="c-form-check-mark"></span>
            </label>
            <label class="c-form-check">
              Trang chủ
              <input type="radio" name="banner_group" data-text="Trang chủ" value="H" {{ old('banner_group') == 'H' ? 'checked' : '' }} class="c-form-check-input">
              <span class="c-form-check-mark"></span>
            </label>
          </div>
          <div class="step-content mb-4">
            <div class="row">
              <div class="col-md-5 selection-box mb-4">
                <div class="row select-paradigm-box {{ old('banner_group') == 'H' ? 'd-none' : '' }}">
                  <div class="col-md-6 mb-2">
                    <x-common.select2-input
                      name="category"
                      :items="$group"
                      item-text="group_name"
                      placeholder="Chọn chuyên mục"
                      items-current-value="{{ old('category') }}"
                      data-selected="{{ old('category') }}"
                      with-child="{{ false }}"
                    />
                  </div>
                  <div class="col-md-6 mb-2">
                    <x-common.select2-input
                      name="paradigm"
                      :items="[]"
                      item-text="group_name"
                      placeholder="Chọn mô hình"
                      items-current-value="{{ old('paradigm') }}"
                      data-selected="{{ old('paradigm') }}"
                      with-child="{{ false }}"
                    />
                  </div>
                </div>
                <div class="position-box bg-white">
                  <div class="device-group-btn d-flex">
                    <div class="radio-with-button device-btn">
                      <input type="radio" name="banner_type" data-text="Desktop" value="D" {{ old('banner_type') != 'M' ? 'checked' : '' }}>
                      <button type="button" class="btn button-with-radio btn-desktop">
                        Desktop
                      </button>
                    </div>
                    <div class="radio-with-button device-btn">
                      <input type="radio" name="banner_type" data-text="Mobile" value="M" {{ old('banner_type') == 'M' ? 'checked' : '' }}>
                      <button type="button" class="btn button-with-radio">
                        Mobile
                      </button>
                    </div>
                  </div>

                  <div class="flex-column p-3 pb-5">
                    <div class="device-show-position desktop-show {{ old('banner_type') != 'M' ? '' : 'd-none' }}">
                      <label class="c-form-check form-check-grey mr-4">
                        Trượt trái
                        <input type="radio" name="banner_position" value="L" {{ old('banner_position') != 'R' ? 'checked' : '' }} class="c-form-check-input">
                        <span class="c-form-check-mark"></span>
                      </label>
                      <label class="c-form-check form-check-grey mr-4">
                        Trượt phải
                        <input type="radio" name="banner_position" value="R" {{ old('banner_position') == 'R' ? 'checked' : '' }} class="c-form-check-input">
                        <span class="c-form-check-mark"></span>
                      </label>
                    </div>
                    <div class="device-show-position mobile-show {{ old('banner_type') == 'M' ? '' : 'd-none' }}">
                      <label class="c-form-check form-check-grey mr-4">
                        Banner mobile
                        <input type="radio" name="banner_position" value="C" {{ old('banner_position') == 'C' ? 'checked' : '' }} class="c-form-check-input">
                        <span class="c-form-check-mark"></span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="note-box bg-white p-3 position-relative">
                  <x-common.loading class="inner"/>
                  <h5 class="fs-18 text-center">Ghi chú:</h5>
                  <div class="note-content"></div>
                </div>
              </div>
              <div class="col-md-7 mb-4">
                <div class="preview-box">
                  <div>
                    <div class="banner-image position-relative {{ old('banner_position') != 'R' ? 'active' : '' }}" data-checked="L">
                      <img src="{{asset('user/images/banner/banner.png')}}" alt="">
                    </div>
                  </div>
                  <img src="{{asset('user/images/banner/group.PNG')}}" alt="" class="main-image">
                  <div>
                    <div class="banner-image position-relative {{ old('banner_position') == 'R' ? 'active' : '' }}" data-checked="R">
                      <img src="{{asset('user/images/banner/banner.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="budget border-bottom">
            {!! $guide->where('config_code', 'N002')->first()->config_value!!}
          </div>
          <div class="text-right mt-4">
            <button type="button" class="btn btn-info px-4" onclick="nextToStep2()">Tiếp >></button>
          </div>
        </div>
      </div>

      <div class="step-section step-2 d-none">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Quảng cáo Express</a></li>
          <li class="breadcrumb-item"><a href="#">Vị trí</a></li>
          <li class="breadcrumb-item"><a href="#">Hình ảnh</a></li>
        </ol>

        <div class="px-3 mb-4">
          <div class="row">
            <div class="col-md-7 campaign-download-image mb-4">
              <div class="upload-item">
                <div class="wrap-out">
                  <div class="wrap-in">
                    <img src="{{asset('user/images/icon/upload-logo.png')}}">
                    <div class="logo-note">Chọn ảnh để tải lên</div>
                    <p class="banner-img-size-desc"></p>
                    <div class="buttons upload">
                      <div class="button button-upload">Upload ảnh</div>
                      <input type="file" name="select_banner_image" accept="image/*" hidden>
                    </div>
                  </div>
                </div>
                {{ show_validate_error($errors, 'select_banner_image') }}
              </div>
              <div class="address">
                <input type="text" name="banner_link" value="{{ old('banner_link') }}" placeholder="Chèn đường dẫn">
              </div>
            </div>
            <div class="col-md-5 images-campaign-2 upload-preview-box mb-4">
              <div class="upload-images h-100 bg-white mb-0">
                <div class="image-size h-100">
                  <div class="upload-image">
                    @if(old('banner_image'))
                      <img class="object-contain" src="{{ old('banner_image') }}" alt="">
                    @endif
                    <input type="text" name="banner_image" value="{{ old('banner_image') }}" hidden>
                    <img class="upload-cover upload-img show-upload-banner-img" src="" style="max-width: 100%;max-height: 100%">
                  </div>
                  <div class="text-right py-2 px-3 upload-preview__action d-none">
                    <a href="javascript:void(0);" class="text-danger js-remove-image mr-3">Xóa</a>
                    <a href="" class="js-view-upload-image-box">
                      Xem
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-7 mb-3">
              <div class="display-time bg-white h-100 mih-450">
                <div class="time-title">Chọn thời gian hiển thị</div>
                <div class="px-4 py-4">
                  <div class="display-time__input-calendar calendar relative">
                    <input type="text" name="banner_date" value="{{ old('banner_date') }}" class="form-control {{ $errors->has('banner_date') ? 'is-invalid' : '' }}" >
                    @if($errors->has('banner_date'))
                      <span class="invalid-feedback w-75  mx-auto pl-1">
                        {{ $errors->first('banner_date') }}
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-5 mb-3">
              <div class="campaign-note bg-white h-100">
                <div class="title-note">Ghi chú</div>
                <div class="note px-3 pb-3 d-block">
                  {!! $guide->where('config_code', 'N003')->first()->config_value !!}
                </div>
              </div>
            </div>
          </div>

          <hr>

          <div class="text-right w-100">
            <button type="button" class="btn btn-secondary px-4" onclick="goToStep(1)"><< Quay lại</button>
            <button type="button" class="btn btn-info px-4" onclick="nextToStep3()">Tiếp >></button>
          </div>
        </div>
      </div>

      <div class="step-section step-3 d-none">
        <div class="create-campaign">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Quảng cáo Express</a></li>
              <li class="breadcrumb-item"><a href="#">Vị trí</a></li>
              <li class="breadcrumb-item"><a href="#">Hình ảnh</a></li>
              <li class="breadcrumb-item"><a href="#">Thanh toán</a></li>
            </ol>
          </nav>
          <div class="campaign-title">
            <a class="title-payment" href="#"><img src="{{asset('user/images/icon/icon-thanhtoan.png')}}" alt="">Thanh Toán</a>
            <p class="title_coin">Chỉ chấp nhận thanh toán bằng <a class="coin" href="{{route('user.deposit')}}">Express Coin</a> </p>
          </div>
          <div class="row campaign-payment-3 ">
            <div class="col-md-6">
              <div class="campaign-cont bg-white h-100">
                <div class="campaign-one">
                  <p class="title-advertisement">Xem lại quảng cáo </p>
                  <div class="advertisement-contents">
                    <div class="information">
                      <div class="alpha display-detail">
                        <ul class="mb-3">
                          <li class="fs-18 mb-1">Chuyên mục: <span id="display-paradigm"></span></li>
                          <li class="fs-18 mb-1">Vị trí hiển thị: <span id="display-position"></span></li>
                          <li class="fs-18 mb-1">Thời gian hiển thị: <span id="display-time"></span></li>
                          <li class="fs-18 mb-1">Số ngày hiển thị: <span id="display-days"></span></li>
                          <li class="fs-18 mb-1">Thiết bị hiển thị: <span id="display-device"></span></li>
                        </ul>
                      </div>
                      <div class="beta">
                        <p class="account-coin">Tài khoản Express Coin</p>
                        <p>Hiên đang có <span class="coin-number">{{auth()->guard('user')->user()->coint_amount}} Coin.</span> Nếu không đủ Coin thanh toán vui lòng tiến
                          hành <a class="load-coin" href="{{route('user.deposit')}}">Nạp Coin</a> </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="campaign-cont bg-white h-100">
                <p class="payments">Thanh toán</p>
                <div class="payment-content">
                  <input type="text" class="form-insert-code" placeholder="NHẬP MÃ ƯU ĐÃI TẠI ĐÂY">
                  <ul>
                    <li>Cần thanh toán: <span id="display-coin"></span> Coin</li>
                    <li>Mã ưu đãi: </li>
                    {{-- <li>Mã ưu đãi: -10% (500 Coin)</li> --}}
                  </ul>
                  <p class="coin-payments">Tổng số coin cần thanh toán</p>
                  <p class="coin-number"><span id="display-total-amount"></span> Coin</p>
                  <button class="btn btn-primary btn-small btn-payout" type="button">Thanh toán</button>
                </div>
              </div>
            </div>
          </div>
          <div class="text-right mt-4">
            <button type="button" class="btn btn-secondary" onclick="goToStep(2)"><< Quay lại</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('script')
  {{-- <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> --}}
  <script src="{{ asset('user/js/new-express.js')}}"></script>
@endsection
