<div class="row">
  <div class="col-md-6">
    <x-common.text-input
      label="Tên sự kiện"
      label-class="bold"
      name="event_title"
      id="event_title"
      value="{{ old('event_title', $event->event_title) }}"
      placeholder="Tên sự kiện"
      required
    />
  </div>

  <div class="col-md-6">
    <x-common.text-input
      label="Ngày diễn ra"
      label-class="bold"
      type="date"
      name="start_date"
      value="{{ old('start_date', $event->start_date ? date('Y-m-d', $event->start_date) : '') }}"
      required
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Địa điểm tổ chức"
      label-class="bold"
      name="take_place"
      value="{{ old('take_place', $event->take_place) }}"
      placeholder="Địa điểm tổ chức"
      required
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Thời gian diễn ra sự kiện"
      label-class="bold"
      type="time"
      name="start_time"
      value="{{ old('start_time', date('H:i', $event->start_date)) }}"
      placeholder="hh:mm"
      required
    />
  </div>
</div>

<x-common.textarea-input
  label="Nội dung sự kiện"
  label-class="bold"
  name="event_content"
  id="event_content"
  value="{!! htmlspecialchars_decode(old('event_content', strip_tags($event->event_content))) !!}"
  placeholder="Nội dung sự kiện"
  required
/>

<div class="upload">
  <div class="upload-item">
    <h4>Tải lên ảnh bìa sự kiện <span>*</span></h4>
    {{-- <div class="wrap-out" data-target="#modalCoverImage" data-toggle="modal"> --}}
    <div class="wrap-out">
      <div class="wrap-in">
        <img src="{{ asset('frontend/images/upload-logo.png') }}">
        <div class="logo-note">
          Kéo & Thả ảnh tại đây!
        </div>
        <p>Bạn chỉ có thể chọn duy nhất một ảnh bìa <br> cho sự kiện</p>
        <div class="buttons file-with-button">
          {{-- <div class="button btn-light-cyan button-upload button-with-file">Tải ảnh lên</div> --}}
          <button type="button" class="btn btn-light-cyan button-with-file px-1 py-0 mr-2 mb-2 fs-14">
            Tải ảnh lên
          </button>
          <button type="button" class="btn btn-light-cyan px-1 py-0 mr-2 mb-2 fs-14">
            Chọn ảnh có sẵn
          </button>
          <input type="file" class="absolute-full opacity-hide" name="select_image_header_url" accept=".png, .jpg, .jpeg">
        </div>
      </div>
    </div>
    {{ show_validate_error($errors, 'image_header_url') }}
  </div>

  <div class="upload-item">
    <h4>Thư mời (nếu có)</h4>
    <div class="wrap-out">
      <div class="wrap-in">
        <img src="{{ asset('frontend/images/upload-logo.png') }}">
        <div class="logo-note">
          Kéo & Thả ảnh tại đây!
        </div>
        <p>Bạn chỉ có thể chọn duy nhất một ảnh thư mời <br> cho sự kiện</p>
        <div class="buttons file-with-button">
          {{-- <div class="button btn-light-cyan button-upload button-with-file">Tải ảnh lên</div> --}}
          <button type="button" class="btn btn-light-cyan button-with-file px-1 py-0 mr-2 mb-2 fs-14">
            Tải ảnh lên
          </button>
          <button type="button" class="btn btn-light-cyan px-1 py-0 mr-2 mb-2 fs-14">
            Chọn ảnh có sẵn
          </button>
          <input type="file" class="absolute-full opacity-hide" name="select_image_invitation_url" accept=".png, .jpg, .jpeg">
        </div>
      </div>
    </div>
    {{ show_validate_error($errors, 'image_invitation_url') }}
  </div>
</div>

<div class="upload-images">
  <div class="upload-image {{ old('image_header_url', $event->image_header_url) ? 'opacity-show' : 'opacity-hide' }}" style="height: 200px">
    <a href="{{ old('image_header_url', $event->image_header_url ? asset($event->image_header_url) : null) ?: 'javascript:void(0);' }}" class="banner-image js-fancy-box">
      <img class="image_header_url upload-img object-contain" src="{{old('image_header_url', $event->image_header_url) }}">
    </a>
    <input type="hidden" name="image_header_url" value="{{ old('image_header_url', $event->image_header_url) }}">
    <i class="fas fa-times-circle remove-img is-opacity-class" data-input="image_header_url"></i>
  </div>

  <div class="upload-image {{ old('image_invitation_url', $event->image_invitation_url) ? 'opacity-show' : 'opacity-hide' }}" style="height: 200px">
    <a href="{{ old('image_invitation_url', $event->image_invitation_url ? asset($event->image_invitation_url) : null) ?: 'javascript:void(0);' }}" class="invite-image js-fancy-box">
      <img class="image_invitation_url upload-img object-contain" src="{{ old('image_invitation_url', $event->image_invitation_url) }}">
    </a>
    <input type="hidden" name="image_invitation_url" value="{{ old('image_invitation_url', $event->image_invitation_url) }}">
    <i class="fas fa-times-circle remove-img is-opacity-class" data-input="image_invitation_url"></i>
  </div>
</div>

<div class="location">
  <strong>Vị trí diễn ra sự kiện <span>*</span></strong>

  <div class="row group-load-address" id="locationRequest">
    <div class="col-md-4">
      <x-common.text-input
        name="address"
        value="{{ old('address', data_get($event->location, 'address')) }}"
        placeholder="Địa chỉ"
        required
      />
    </div>

    {{-- <div class="form-group">
      <input class="w-100" type="text" name="address" id="road" placeholder="Địa chỉ"
        value="{{ old('address', data_get($event->location, 'address')) }}">
      {{ show_validate_error($errors, 'address') }}
    </div> --}}

    <div class="col-md-4">
      <x-common.select2-input
        input-class="province-load-district"
        name="province_id"
        id="province"
        placeholder="-- Tỉnh/Thành Phố  --"
        :items="$provinces"
        item-text="province_name"
        items-select-name="province_id"
        :items-current-value="old('province_id', data_get($event->location, 'province_id'))"
      />
    </div>

    <div class="col-md-4">
      <x-common.select2-input
        input-class="district-province"
        name="district_id"
        id="district"
        placeholder="-- Quận/Huyện --"
        :data-selected="old('district_id', data_get($event->location, 'district_id'))"
      />
    </div>
  </div>

  <div>
    <x-common.map
      id="event-form__map"
      lat-name="map_latitude"
      long-name="map_longtitude"
      lat-value="{{ old('map_latitude', data_get($event->location, 'map_latitude')) }}"
      long-value="{{ old('map_longtitude', data_get($event->location, 'map_longtitude')) }}"
    />
  </div>

  {{-- <div class="iframe">
    <div id="mapEvent" style="height: 500px"></div>
    <div class="note">Click để thay đổi vị trí trên bản đồ</div>
    <input type="hidden" name="map_latitude" id="map_latitude" value="{{ old('map_latitude', data_get($event->location, 'map_latitude')) }}">
    <input type="hidden" name="map_longtitude" id="map_longtitude" value="{{ old('map_longtitude', data_get($event->location, 'map_longtitude')) }}">
  </div> --}}
</div>

<div class="top">
  <div class="on-off-custom">
    <div class="on-off-toggle">
      <input class="on-off-toggle__input" type="checkbox" id="project_highlight" name="is_highlight" value="1"
        {{ old('is_highlight', $event->isHighLight()) ? 'checked' : '' }} />
      <label for="project_highlight" class="on-off-toggle__slider"></label>
    </div>
  </div>

  @if ($serviceFee)
    <div class="note">
      <p>Sau khi bật sự kiện sẽ đứng đầu danh sách.</p>
      <p><strong>Phí duy trì: <span class="red">{{ data_get($serviceFee, 'service_fee') }}
            coin/{{ ceil(data_get($serviceFee, 'existence_time') / 86400) }} ngày</span></strong></p>
      <p>Bạn có: <span class="green"><strong>{{ auth('user')->user()->coint_amount ?? 0 }} coin</strong></span></p>
    </div>
  @endif
</div>
<input type="hidden" id="event_url" name="event_url" value="{{ old('event_url', $event->event_url ?: $event->event_title) }}" />
<input type="hidden" id="meta_title" name="meta_title" value="{{ old('meta_title', $event->meta_title ?: $event->event_title) }}" />
<input type="hidden" id="meta_keyword" name="meta_key" value="{{ old('meta_key', $event->meta_key ?: $event->event_title) }}" />
<input type="hidden" id="meta_desc" name="meta_desc" value="{{ old('meta_desc', $event->meta_desc ?: $event->event_title) }}" />
