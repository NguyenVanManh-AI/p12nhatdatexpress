<div class="modal fade" id="guest-add-classified__modal" tabindex="-1" role="dialog"
  aria-labelledby="guest-add-classified__label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xxl" role="document">
    <div class="modal-content classified-page guest-classified-page js-change-address-load-map js-has-map-address">
      <div class="modal-body bg-gray-f5">
        <button type="button" class="close custom-rounded-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

        <div class="row pb-4 radio-with-button-group guest-add-classified__switch-category border-bottom-white">
          <div class="col-md-4">
            <div class="card guest-add-classified__switch-button js-radio-toggle-active h-100 active">
              <div class="radio-with-button flex-between flex-column w-100 h-100">
                <div class="py-4 d-flex align-items-end flex-1 guest-add-classified__switch-image">
                  <img src="{{ asset('frontend/user/images/house.png') }}" alt="">
                </div>
                <input type="radio" name="category" checked value="nha-dat-ban">
                <div
                  class="w-100 guest-add-classified__switch-label border-top text-center bg-white p-2 px-3 text-blue font-weight-bold fs-18">
                  Đăng tin nhà đất bán
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card guest-add-classified__switch-button js-radio-toggle-active h-100">
              <div class="radio-with-button flex-between flex-column w-100 h-100">
                <div class="py-4 d-flex align-items-end flex-1 guest-add-classified__switch-image">
                  <img src="{{ asset('frontend/user/images/house2.png') }}" alt="">
                </div>
                <input type="radio" name="category" value="nha-dat-ban">
                <div
                  class="w-100 guest-add-classified__switch-label border-top text-center bg-white p-2 px-3 text-blue font-weight-bold fs-18">
                  Đăng tin nhà đất cho thuê
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card guest-add-classified__switch-button js-radio-toggle-active h-100">
              <div class="radio-with-button flex-between flex-column w-100 h-100">
                <div class="py-4 d-flex align-items-end flex-1 guest-add-classified__switch-image">
                  <img src="{{ asset('frontend/user/images/house3.png') }}" alt="">
                </div>
                <input type="radio" name="category" value="nha-dat-ban">
                <div
                  class="w-100 guest-add-classified__switch-label border-top text-center bg-white p-2 px-3 text-blue font-weight-bold fs-18">
                  Đăng tin cần mua/cần thuê
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="pt-4">
          @include('user.classified.partials._title-form', [
              'group' => $group,
              'guide' => $guide,
              'classified' => $classified,
          ])

          <div>
            <div class="row">
              @include('user.classified.partials._project-form', [
                  'project' => $project,
                  'group' => $group,
                  'group_parent_id' => null,
                  'classified' => $classified,
                  'location' => new \App\Models\Classified\ClassifiedLocation(),
                  'account_verify' => false,
                  'isHideUtilities' => true,
              ])
            </div>

            <div class="map-form-land mb-4">
              <div class="row">
                <div class="col-12 col-md-8">
                  <div class="col-12">
                    <div class="mb-3">
                      <div class="mb-3">
                        <x-common.map
                          id="classified-page__map"
                          lat-name="latitude"
                          long-name="longtitude"
                          lat-value="{{ old('latitude') }}"
                          long-value="{{ old('longtitude') }}"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mt-1 pt-1 mb-3">
                    <div class="upload-image-land mb-3">
                      <div class="title-upload-img-land">
                        <div class="upload pt-0">
                          <div class="upload-item">
                            <div class="wrap-out bg-white p-3">
                              <div class="wrap-in">
                                <input type="file" ondragover="allowDrop(event)" name="temp_upload_image"
                                  accept="image/*" multiple>
                                <img src="{{ asset('user/images/icon/upload-logo.png') }}">
                                <div class="logo-note">
                                  Kéo & Thả ảnh tại đây!
                                </div>
                                <p>Bạn có thể chọn nhiều hình ảnh để tải lên cùng một lúc</p>
                                <div class="buttons">
                                  <button type="button" class="btn text-white btn-upload-image btn-blue">
                                    Tải ảnh lên
                                  </button>
                                  <input type="file" name="temp_upload_image" accept="image/*" multiple hidden>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div
                      class="classified-page__upload-images-box update-slider-image mb-4 {{ old('gallery') || old('gallery_project') ? '' : 'd-none' }}">
                      <div class="classified-page__slick-list list-update-slider-image slick mb-2">
                        @foreach (old('gallery') ?? [] as $item)
                          <div class="item-slick-land">
                            <div class="image-ratio-box relative">
                              <div class="absolute-full">
                                <img class="object-cover shadow" src="{{ $item }}" alt="">
                                <input type="hidden" name="gallery[]" value="{{ $item }}">
                              </div>
                              <i class="fas fa-times cursor-pointer remove-land"></i>
                            </div>
                          </div>
                        @endforeach
                        @foreach (old('gallery_project') ?? [] as $item)
                          <div class="item-slick-land">
                            <div class="image-ratio-box relative">
                              <div class="absolute-full">
                                <img class="object-cover shadow" src="{{ $item }}" alt="">
                                <input type="hidden" name="gallery_project[]" value="{{ $item }}">
                              </div>
                              <i class="fas fa-times cursor-pointer remove-land"></i>
                            </div>
                          </div>
                        @endforeach
                      </div>
                      <div class="notication-update-slider-image">Hình ảnh tin đăng</div>
                      <input type="text" id="inp-images-list" onchange="imagesList(this)" hidden>
                    </div>
                    {{ show_validate_error($errors, 'gallery') }}

                    {{-- should change add fields step by step keep position --}}
                    @include('user.classified.partials._form', [
                        'selectedProject' => $project_active ?? null,
                    ])

                    {{-- @include('user.classified.partials._meta-form', [
                              'classified' => $classified,
                          ]) --}}
                  </div>
                  <div class="col-12">
                    <div class="p-3 bg-blue rounded">
                      <div class="row">
                        <div class="col-12">
                          <h4 class="normal-title text-uppercase text-center text-white mb-4">Thông tin liên hệ
                          </h4>
                        </div>
                        <div class="col-md-6">
                          <x-common.text-input
                            name="contact_name"
                            value="{{ old('contact_name') }}"
                            placeholder="Nhập Họ & Tên"
                          >
                            <x-slot name="prependInner">
                              <div class="c-icon text-gray">
                                <i class="fas fa-user"></i>
                              </div>
                            </x-slot>
                          </x-common.text-input>
                        </div>
                        <div class="col-md-6">
                          <x-common.text-input
                            name="contact_phone"
                            value="{{ old('contact_phone') }}"
                            placeholder="Nhập Số điện thoại"
                          >
                            <x-slot name="prependInner">
                              <div class="c-icon text-gray">
                                <i class="fas fa-phone-alt"></i>
                              </div>
                            </x-slot>
                          </x-common.text-input>
                        </div>
                        <div class="col-md-6">
                          <x-common.text-input
                            name="contact_email"
                            type="email"
                            value="{{ old('contact_email') }}"
                            placeholder="Nhập email"
                          >
                            <x-slot name="prependInner">
                              <div class="c-icon text-gray">
                                <i class="fas fa-envelope"></i>
                              </div>
                            </x-slot>
                          </x-common.text-input>
                        </div>
                        <div class="col-md-6">
                          <x-common.text-input
                            name="contact_address"
                            value="{{ old('contact_address') }}"
                            placeholder="Nhập địa chỉ"
                          >
                            <x-slot name="prependInner">
                              <div class="c-icon text-gray">
                                <i class="fas fa-map-marker-alt"></i>
                              </div>
                            </x-slot>
                          </x-common.text-input>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-4 time-right classified-time-preview">
                  <div class="child-time-right sticky top-3">
                    <div>
                      <div class="time-post-project">
                        <div class="title-time-post">Lịch đăng tin</div>
                        <div class="bg-white px-3 py-2">
                          <div class="total-date">
                            <div class="sice-date form-group">
                              <label for="date_from">Từ ngày</label>
                              <input type="date" name="date_from" id="date_from" class="form-control"
                                value="{{ old('date_from', now()->format('Y-m-d')) }}">
                            </div>
                            <div class="until-date form-group">
                              <label for="date_to">Đến ngày</label>
                              <input type="date" name="date_to"
                                value="{{ old('date_to',now()->addDays(59)->format('Y-m-d')) }}" id="date_to"
                                class="form-control ">
                            </div>
                          </div>
                          <div class="total-date-post-land">Tổng số ngày đăng:
                            <span class="important total-days">60</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="create-post-land">
                        <div class="title-time-post">Xem trước & đăng tin</div>
                        <div class="bg-white px-3 py-2">
                          <div class="mb-4">
                            <x-common.captcha />
                          </div>

                          <div class="list-payment-post-land d-flex justify-content-end">
                            <div class="group">
                              <a type="submit" class="btn btn-success js-classified-preview" data-toggle="modal"
                                data-target="#preview">Xem trước</a>
                              <i class="fas fa-tv"></i>
                            </div>
                            <div class="group">
                              <a href="javascript:void(0);" class="btn post submit">Đăng</a>
                              <i class="fas fa-arrow-up"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
